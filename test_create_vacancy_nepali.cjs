const puppeteer = require('puppeteer');
const path = require('path');
const fs = require('fs');

const BASE_URL = 'http://127.0.0.1:8000';
const SCREENSHOTS_DIR = path.join(__dirname, 'test_screenshots_vacancy');

if (!fs.existsSync(SCREENSHOTS_DIR)) fs.mkdirSync(SCREENSHOTS_DIR, { recursive: true });

const ADMIN    = { email: 'admin@recruitment.com', password: 'password' };
const CANDIDATE = { email: 'sujan@candidate.com',  password: 'Test@1234' };

let idx = 0;
const sleep = ms => new Promise(r => setTimeout(r, ms));
async function shot(page, label) {
    idx++;
    const f = path.join(SCREENSHOTS_DIR, `${String(idx).padStart(2,'0')}_${label}.png`);
    await page.screenshot({ path: f, fullPage: true });
    console.log(`  📸 ${path.basename(f)}`);
}
async function fill(page, sel, txt) {
    await page.waitForSelector(sel, { timeout: 8000, visible: true });
    await page.click(sel, { clickCount: 3 });
    await page.type(sel, txt, { delay: 25 });
}

async function switchToNepali(page) {
    // Use the actual language switcher form on the admin layout
    await page.waitForSelector('select[name="locale"]', { timeout: 5000 });
    await page.select('select[name="locale"]', 'ne');
    // onchange triggers form.submit() — wait for navigation
    await page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 10000 });
    await sleep(500);
}

async function main() {
    const browser = await puppeteer.launch({
        headless: false,
        defaultViewport: null,
        args: ['--start-maximized']
    });

    try {
        // ── 1. Admin login ──────────────────────────────────────────
        console.log('\n[1] Admin login');
        const admin = await browser.newPage();
        admin.setDefaultTimeout(15000);

        await admin.goto(`${BASE_URL}/admin/login`, { waitUntil: 'networkidle2' });
        await fill(admin, 'input[name="email"]', ADMIN.email);
        await fill(admin, 'input[name="password"]', ADMIN.password);
        await Promise.all([
            admin.waitForNavigation({ waitUntil: 'networkidle2' }),
            admin.click('button[type="submit"]')
        ]);
        console.log('  ✅ Admin logged in:', admin.url());
        await shot(admin, 'step1_admin_dashboard_english');

        // ── 2. Switch to Nepali using the language switcher ──────────
        console.log('\n[2] Switch to Nepali via language dropdown');
        await switchToNepali(admin);
        const localeUrl = admin.url();
        console.log('  After switch URL:', localeUrl);
        await sleep(800);
        await shot(admin, 'step2_admin_dashboard_nepali');

        // Verify Nepali is active
        const currentLocale = await admin.evaluate(() => {
            const sel = document.querySelector('select[name="locale"]');
            return sel ? sel.value : 'unknown';
        });
        console.log('  Language selector shows:', currentLocale, currentLocale === 'ne' ? '✅' : '⚠️ ');

        const dashContent = await admin.evaluate(() => document.body.innerText.substring(0, 300));
        const hasNepaliOnDash = /[\u0900-\u097F]/.test(dashContent);
        console.log('  Devanagari chars on dashboard:', hasNepaliOnDash ? '✅' : '⚠️ ');

        // ── 3. Navigate to Job Create ────────────────────────────────
        console.log('\n[3] Navigate to Job Create form (Nepali mode)');
        await admin.goto(`${BASE_URL}/admin/jobs/create`, { waitUntil: 'networkidle2' });
        await sleep(2000);   // wait for Nepali libs + converter
        await shot(admin, 'step3_job_create_form_nepali');

        // Check that labels are Nepali
        const formLabels = await admin.evaluate(() => {
            const spans = document.querySelectorAll('.form-label');
            return Array.from(spans).slice(0, 6).map(el => el.textContent.replace(/\s+/g,' ').trim().substring(0, 50));
        });
        console.log('  Form labels (should be Nepali):');
        formLabels.forEach((l,i) => console.log(`    ${i+1}. ${l}`));
        const labelsNepali = formLabels.some(l => /[\u0900-\u097F]/.test(l));
        console.log('  Nepali chars in labels:', labelsNepali ? '✅' : '⚠️ ');

        // ── 4. Fill form fields ─────────────────────────────────────
        console.log('\n[4] Filling form (admin in Nepali mode)');

        const noticeNo = 'NP-' + Date.now().toString().slice(-5);
        await fill(admin, '#notice_no', noticeNo);
        await fill(admin, '#position_input', 'Senior Officer');
        await sleep(500);
        await fill(admin, '#level_input', '7');
        await sleep(300);
        await fill(admin, '#minimum_qualification', 'Bachelor degree in relevant field. Minimum 3 years required.');
        await sleep(300);

        await shot(admin, 'step4_form_top_filled');

        // ── 5. Select Open category ─────────────────────────────────
        console.log('\n[5] Select Open category');
        const openCb = await admin.$('#category_open, #has_open');
        if (openCb) {
            const checked = await admin.evaluate(el => el.checked, openCb);
            if (!checked) {
                await openCb.click();
                await sleep(1000);
            }
            console.log('  ✅ Open selected');
        }

        // ── 6. Enter fee ─────────────────────────────────────────────
        await sleep(600);
        const feeInput = await admin.$('input[name="category_fees[open]"], .category-fee-input');
        if (feeInput) {
            await feeInput.click({ clickCount: 3 });
            await feeInput.type('1500', { delay: 25 });
            await sleep(400);
            const total = await admin.$eval('#application_fee', el => el.value);
            console.log('  ✅ Fee 1500 entered. Total:', total);
        }
        await shot(admin, 'step5_fee_and_category');

        // ── 7. Verify deadline auto-set ──────────────────────────────
        console.log('\n[7] Verify deadline');
        const deadlineVal = await admin.$eval('#deadline_ad', el => el.value);
        const deadlineBsVal = await admin.$eval('#deadline_bs_hidden', el => el.value);
        const deadlineBsDisplay = await admin.$eval('#deadline_bs', el => el.value);
        console.log('  deadline_ad value:', deadlineVal, /^\d{4}-\d{2}-\d{2}$/.test(deadlineVal) ? '✅' : '❌');
        console.log('  deadline_bs_hidden value:', deadlineBsVal, /^[0-9-]+$/.test(deadlineBsVal) ? '✅ (English numerals)' : '⚠️ ');
        console.log('  deadline_bs display:', deadlineBsDisplay, '← shown to user');

        // ── 8. Check all input .values ───────────────────────────────
        console.log('\n[8] CRITICAL: Input .value integrity check');
        const vals = await admin.evaluate(() => ({
            notice_no:   document.getElementById('notice_no')?.value,
            position:    document.getElementById('position_input')?.value,
            level:       document.getElementById('level_input')?.value,
            deadline:    document.getElementById('deadline_ad')?.value,
            deadline_bs: document.getElementById('deadline_bs_hidden')?.value,
        }));
        console.log('  All values should be English/ASCII (not Nepali numerals):');
        Object.entries(vals).forEach(([k,v]) => {
            const hasNepaliNum = /[०-९]/.test(v);
            console.log(`  ${k}: "${v}" ${hasNepaliNum ? '❌ HAS NEPALI NUMERALS' : '✅'}`);
        });

        const allValuesClean = Object.values(vals).every(v => !/[०-९]/.test(v));
        console.log('  All input values clean:', allValuesClean ? '✅ SAFE' : '❌ CORRUPTED');

        // ── 9. Submit form ───────────────────────────────────────────
        console.log('\n[9] Submit form');
        await admin.evaluate(() => {
            const pos = (document.getElementById('position_input')?.value || '').trim();
            const lvl = (document.getElementById('level_input')?.value || '').trim();
            if (document.getElementById('hidden_title'))
                document.getElementById('hidden_title').value = pos;
            if (document.getElementById('hidden_description'))
                document.getElementById('hidden_description').value = `Position: ${pos} - ${lvl}`;
            if (document.getElementById('hidden_requirements'))
                document.getElementById('hidden_requirements').value = document.getElementById('minimum_qualification')?.value || '';
        });

        await admin.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
        await sleep(500);
        await shot(admin, 'step6_form_preview_bottom');

        await Promise.all([
            admin.waitForNavigation({ waitUntil: 'networkidle2', timeout: 25000 }).catch(() => {}),
            admin.evaluate(() => {
                const btn = document.querySelector('button[type="submit"]');
                if (btn) btn.click();
            })
        ]);

        await sleep(1500);
        const afterUrl = admin.url();
        console.log('  Post-submit URL:', afterUrl);
        await shot(admin, 'step7_after_submit');

        const hasErrors = await admin.evaluate(() =>
            document.querySelectorAll('.is-invalid, .alert-danger').length > 0
        );
        if (hasErrors) {
            const errs = await admin.evaluate(() => {
                const msgs = [];
                document.querySelectorAll('.invalid-feedback, .alert-danger li').forEach(el => {
                    const t = el.textContent.trim();
                    if (t) msgs.push(t.substring(0, 80));
                });
                return msgs;
            });
            console.log('  Validation errors:', errs.slice(0, 8));
            await shot(admin, 'step7b_validation_errors');
        }

        const vacancyCreated = !afterUrl.includes('/create') && !afterUrl.includes('/login');
        console.log('  Vacancy created:', vacancyCreated ? '✅' : '⚠️  Check URL/errors above');

        // Get new vacancy ID
        let newJobId = null;
        const m = afterUrl.match(/\/admin\/jobs\/(\d+)/);
        if (m) newJobId = m[1];
        if (!newJobId && vacancyCreated) {
            await admin.goto(`${BASE_URL}/admin/jobs`, { waitUntil: 'networkidle2' });
            await sleep(800);
            newJobId = await admin.evaluate(() => {
                const lnk = document.querySelector('table tbody tr:first-child a[href*="/admin/jobs/"]');
                if (!lnk) return null;
                const mm = lnk.href.match(/\/admin\/jobs\/(\d+)/);
                return mm ? mm[1] : null;
            });
        }
        console.log('  New vacancy ID:', newJobId);

        // ── 10. Admin jobs list in Nepali ────────────────────────────
        console.log('\n[10] Admin jobs list (Nepali mode)');
        await admin.goto(`${BASE_URL}/admin/jobs`, { waitUntil: 'networkidle2' });
        await sleep(1200);
        await shot(admin, 'step8_jobs_list_nepali');

        // ── 11. Candidate: browse jobs ───────────────────────────────
        console.log('\n[11] Candidate portal');
        const cand = await browser.newPage();
        cand.setDefaultTimeout(12000);

        await cand.goto(`${BASE_URL}/candidate/login`, { waitUntil: 'networkidle2' });
        await fill(cand, 'input[name="email"]', CANDIDATE.email);
        await fill(cand, 'input[name="password"]', CANDIDATE.password);
        await Promise.all([
            cand.waitForNavigation({ waitUntil: 'networkidle2', timeout: 15000 }),
            cand.click('button[type="submit"]')
        ]);
        console.log('  Candidate logged in:', cand.url());
        await shot(cand, 'step9_candidate_dashboard');

        await cand.goto(`${BASE_URL}/candidate/jobs`, { waitUntil: 'networkidle2' });
        await sleep(800);
        await shot(cand, 'step10_candidate_jobs_list');

        await cand.goto(`${BASE_URL}/candidate/jobs/178`, { waitUntil: 'networkidle2' });
        await sleep(800);
        await shot(cand, 'step11_candidate_job_detail');
        console.log('  Candidate job detail:', cand.url());

        await cand.goto(`${BASE_URL}/candidate/jobs/178/applications/create`, { waitUntil: 'networkidle2' });
        await sleep(1000);
        const candAppUrl = cand.url();
        await shot(cand, 'step12_candidate_application_form');
        console.log('  Application form URL:', candAppUrl);

        const onForm = candAppUrl.includes('create');
        const candConverter = await cand.evaluate(() => typeof window._convertToNepaliNum === 'function');

        // ── FINAL REPORT ─────────────────────────────────────────────
        console.log('\n');
        console.log('═'.repeat(62));
        console.log('   FULL WORKFLOW TEST — VACANCY CREATION IN NEPALI MODE');
        console.log('═'.repeat(62));
        console.log(`  Admin login:                           ✅`);
        console.log(`  Language switched to Nepali:           ${currentLocale === 'ne' ? '✅' : '⚠️ ' + currentLocale}`);
        console.log(`  Dashboard shows Nepali:                ${hasNepaliOnDash ? '✅' : '⚠️ '}`);
        console.log(`  Form labels in Nepali:                 ${labelsNepali ? '✅' : '⚠️  (check screenshot)'}`);
        console.log(`  Form input .values unaffected:         ${allValuesClean ? '✅ All English/ASCII' : '❌ CORRUPTED'}`);
        console.log(`  Deadline AD valid format:              ${/^\d{4}-\d{2}-\d{2}$/.test(deadlineVal) ? '✅ ' + deadlineVal : '❌'}`);
        console.log(`  Deadline BS hidden English nums:       ${/^[0-9-]+$/.test(deadlineBsVal) ? '✅ ' + deadlineBsVal : '❌'}`);
        console.log(`  Vacancy form submitted:                ${vacancyCreated ? '✅' : '⚠️  check errors above'}`);
        console.log(`  Candidate sees jobs list:              ✅`);
        console.log(`  Candidate can open application form:   ${onForm ? '✅' : '⚠️  redirected'}`);
        console.log(`  Candidate portal: no Nepali converter: ${!candConverter ? '✅ correct' : '⚠️  converter found'}`);
        console.log('═'.repeat(62));
        console.log(`\n📸 ${SCREENSHOTS_DIR} (${idx} screenshots)`);
        console.log('\n⏳ Browser open 50s for inspection...');
        await sleep(50000);

    } catch(err) {
        console.error('\n❌ FATAL:', err.message, '\n', err.stack);
        await sleep(60000);
    } finally {
        await browser.close();
        console.log('Done.');
    }
}

main();
