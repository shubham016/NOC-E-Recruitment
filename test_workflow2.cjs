const puppeteer = require('puppeteer');
const path = require('path');
const fs = require('fs');

const BASE_URL = 'http://127.0.0.1:8000';
const SCREENSHOTS_DIR = path.join(__dirname, 'test_screenshots2');

const ADMIN     = { email: 'admin@recruitment.com', password: 'password' };
const CANDIDATE = { email: 'sujan@candidate.com',   password: 'Test@1234' };
const REVIEWER  = { email: 'suresh@reviewer.com',   password: 'Test@1234' };
const APPROVER  = { employee_id: '12345',            password: 'Test@1234' };

const EXISTING_APP_ID = 169;
const REVIEWER_ID     = 3;
const APPROVER_ID     = 5;
const ACTIVE_JOB_ID   = 178;

if (!fs.existsSync(SCREENSHOTS_DIR)) fs.mkdirSync(SCREENSHOTS_DIR, { recursive: true });

let idx = 0;
const sleep = ms => new Promise(r => setTimeout(r, ms));

async function shot(page, label) {
    idx++;
    const file = path.join(SCREENSHOTS_DIR, `${String(idx).padStart(2,'0')}_${label}.png`);
    await page.screenshot({ path: file, fullPage: true });
    console.log(`  📸 ${path.basename(file)}`);
    return file;
}

async function fill(page, sel, txt) {
    await page.waitForSelector(sel, { timeout: 8000 });
    await page.click(sel, { clickCount: 3 });
    await page.type(sel, txt, { delay: 20 });
}

const results = [];
function log(step, ok, note = '') {
    results.push({ step, ok, note });
    const icon = ok === true ? '✅' : ok === false ? '❌' : '⚠️ ';
    console.log(`  ${icon} ${step}${note ? ' — ' + note : ''}`);
}

async function main() {
    // Reset app to submitted state before test
    console.log('Pre-test: resetting application 169 to submitted state...');

    const browser = await puppeteer.launch({
        headless: false,
        defaultViewport: null,
        args: ['--start-maximized', '--disable-infobars']
    });

    try {
        // ════════════════════════════════════════════════════════════
        //  ADMIN PORTAL — NEPALI MODE
        // ════════════════════════════════════════════════════════════
        console.log('\n══════════════════════════════════════════════════════');
        console.log('  PHASE 1 — ADMIN PORTAL (NEPALI MODE)');
        console.log('══════════════════════════════════════════════════════');

        const admin = await browser.newPage();
        admin.setDefaultTimeout(12000);

        // Login
        await admin.goto(`${BASE_URL}/admin/login`, { waitUntil: 'networkidle2' });
        await fill(admin, 'input[name="email"]', ADMIN.email);
        await fill(admin, 'input[name="password"]', ADMIN.password);
        await Promise.all([
            admin.waitForNavigation({ waitUntil: 'networkidle2', timeout: 15000 }),
            admin.click('button[type="submit"]')
        ]);
        const adminLoggedIn = admin.url().includes('/admin/dashboard');
        log('Admin login', adminLoggedIn, admin.url());
        await shot(admin, 'admin_01_logged_in');

        // Switch to Nepali
        await admin.goto(`${BASE_URL}/set-locale/ne`, { waitUntil: 'networkidle2' });
        await admin.goto(`${BASE_URL}/admin/dashboard`, { waitUntil: 'networkidle2' });
        await sleep(1500);
        const dashText = await admin.evaluate(() => document.body.innerText.substring(0, 500));
        const hasNepali = /[\u0900-\u097F]/.test(dashText);
        log('Nepali mode active (Devanagari chars detected)', hasNepali);
        await shot(admin, 'admin_02_dashboard_nepali');

        // Jobs list
        await admin.goto(`${BASE_URL}/admin/jobs`, { waitUntil: 'networkidle2' });
        await sleep(1200);
        await shot(admin, 'admin_03_jobs_list_nepali');
        log('Jobs list loads in Nepali', !admin.url().includes('error'));

        // Job create form — key test for converter + form functionality
        await admin.goto(`${BASE_URL}/admin/jobs/create`, { waitUntil: 'networkidle2' });
        await sleep(1500);
        await shot(admin, 'admin_04_job_create_nepali');
        log('Job create form loads in Nepali', !admin.url().includes('error'));

        // Verify form inputs still work (type in notice_no field)
        await fill(admin, '#notice_no', 'TEST-NE-' + Date.now().toString().slice(-4));
        const noticeVal = await admin.$eval('#notice_no', el => el.value);
        log('Form input .value unaffected by Nepali converter', noticeVal.startsWith('TEST-NE-'));
        await shot(admin, 'admin_05_form_input_test');

        // Applications list
        await admin.goto(`${BASE_URL}/admin/applications`, { waitUntil: 'networkidle2' });
        await sleep(1500);
        await shot(admin, 'admin_06_applications_nepali');
        log('Applications list loads in Nepali', !admin.url().includes('error'));

        // Open modal for app 169 — check modal content in Nepali
        try {
            // Find the view button with data-id or data-appid
            const viewBtnSel = `[data-id="${EXISTING_APP_ID}"], [data-app-id="${EXISTING_APP_ID}"], tr[data-id="${EXISTING_APP_ID}"] button, button.btn-view-app`;
            // Try clicking the first row's view/eye button
            const viewBtn = await admin.evaluate((appId) => {
                // Find buttons with onclick containing the app id
                const btns = document.querySelectorAll('button, a');
                for (const b of btns) {
                    const oc = b.getAttribute('onclick') || b.getAttribute('data-id') || '';
                    if (oc.includes(String(appId))) return true;
                }
                return false;
            }, EXISTING_APP_ID);

            if (viewBtn) {
                // Use JavaScript to click it
                await admin.evaluate((appId) => {
                    const btns = document.querySelectorAll('button, a');
                    for (const b of btns) {
                        const oc = b.getAttribute('onclick') || b.getAttribute('data-id') || '';
                        if (oc.includes(String(appId))) { b.click(); return; }
                    }
                }, EXISTING_APP_ID);
                await sleep(1500);
                await shot(admin, 'admin_07_application_modal_nepali');
                const modalVisible = await admin.evaluate(() => {
                    const modal = document.querySelector('.modal.show, .modal[style*="display: block"]');
                    return !!modal;
                });
                log('Application modal opens (Nepali numbers in modal)', modalVisible);
                // Close modal
                await admin.evaluate(() => {
                    const close = document.querySelector('.modal .btn-close, .modal [data-bs-dismiss="modal"]');
                    if (close) close.click();
                });
                await sleep(500);
            } else {
                log('Application modal test', null, 'view button not found on page');
            }
        } catch(e) {
            log('Application modal test', null, e.message);
        }

        // Candidates page
        await admin.goto(`${BASE_URL}/admin/candidates`, { waitUntil: 'networkidle2' });
        await sleep(1200);
        await shot(admin, 'admin_08_candidates_nepali');
        log('Candidates page loads in Nepali', !admin.url().includes('error'));

        // Assign Reviewer to App 169 via POST AJAX
        const csrfToken = await admin.evaluate(() => {
            const m = document.querySelector('meta[name="csrf-token"]');
            return m ? m.content : '';
        });

        const assignRevRes = await admin.evaluate(async (appId, revId, csrf, base) => {
            const r = await fetch(`${base}/admin/applications/${appId}/assign-reviewer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ reviewer_id: revId })
            });
            const text = await r.text();
            let isJson = false;
            try { JSON.parse(text); isJson = true; } catch(e) {}
            return { status: r.status, isJson, preview: text.substring(0, 80) };
        }, EXISTING_APP_ID, REVIEWER_ID, csrfToken, BASE_URL);

        log('Assign reviewer API', assignRevRes.status === 200 && assignRevRes.isJson,
            `HTTP ${assignRevRes.status} | JSON: ${assignRevRes.isJson} | ${assignRevRes.preview}`);

        // Assign Approver to App 169
        const assignApprRes = await admin.evaluate(async (appId, apprId, csrf, base) => {
            const r = await fetch(`${base}/admin/applications/${appId}/assign-approver`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ approver_id: apprId })
            });
            const text = await r.text();
            let isJson = false;
            try { JSON.parse(text); isJson = true; } catch(e) {}
            return { status: r.status, isJson, preview: text.substring(0, 80) };
        }, EXISTING_APP_ID, APPROVER_ID, csrfToken, BASE_URL);

        log('Assign approver API', assignApprRes.status === 200 && assignApprRes.isJson,
            `HTTP ${assignApprRes.status} | JSON: ${assignApprRes.isJson} | ${assignApprRes.preview}`);

        // Reports
        await admin.goto(`${BASE_URL}/admin/reports`, { waitUntil: 'networkidle2' });
        await sleep(1000);
        await shot(admin, 'admin_09_reports_nepali');
        log('Reports page loads in Nepali', !admin.url().includes('error'));

        // ════════════════════════════════════════════════════════════
        //  CANDIDATE PORTAL
        // ════════════════════════════════════════════════════════════
        console.log('\n══════════════════════════════════════════════════════');
        console.log('  PHASE 2 — CANDIDATE PORTAL');
        console.log('══════════════════════════════════════════════════════');

        const cand = await browser.newPage();
        cand.setDefaultTimeout(12000);

        await cand.goto(`${BASE_URL}/candidate/login`, { waitUntil: 'networkidle2' });
        await fill(cand, 'input[name="email"]', CANDIDATE.email);
        await fill(cand, 'input[name="password"]', CANDIDATE.password);
        await Promise.all([
            cand.waitForNavigation({ waitUntil: 'networkidle2', timeout: 15000 }),
            cand.click('button[type="submit"]')
        ]);
        log('Candidate login', cand.url().includes('/candidate'), cand.url());
        await shot(cand, 'cand_01_dashboard');

        await cand.goto(`${BASE_URL}/candidate/jobs`, { waitUntil: 'networkidle2' });
        await sleep(800);
        await shot(cand, 'cand_02_jobs_list');
        log('Candidate jobs list', !cand.url().includes('error'));

        await cand.goto(`${BASE_URL}/candidate/jobs/${ACTIVE_JOB_ID}`, { waitUntil: 'networkidle2' });
        await sleep(800);
        await shot(cand, 'cand_03_job_detail');
        log('Candidate job detail', !cand.url().includes('error'));

        // Application form
        await cand.goto(`${BASE_URL}/candidate/jobs/${ACTIVE_JOB_ID}/applications/create`, { waitUntil: 'networkidle2' });
        await sleep(1000);
        const appUrl = cand.url();
        await shot(cand, 'cand_04_application_form');
        const onForm = appUrl.includes('/create') || appUrl.includes('applications');
        log('Candidate application form accessible', onForm, appUrl.includes('create') ? 'on form ✓' : 'redirected: ' + appUrl);

        // My applications
        await cand.goto(`${BASE_URL}/candidate/applications`, { waitUntil: 'networkidle2' });
        await sleep(800);
        await shot(cand, 'cand_05_my_applications');
        log('Candidate my applications page', !cand.url().includes('error'));

        // ════════════════════════════════════════════════════════════
        //  REVIEWER PORTAL
        // ════════════════════════════════════════════════════════════
        console.log('\n══════════════════════════════════════════════════════');
        console.log('  PHASE 3 — REVIEWER PORTAL');
        console.log('══════════════════════════════════════════════════════');

        const rev = await browser.newPage();
        rev.setDefaultTimeout(12000);

        await rev.goto(`${BASE_URL}/reviewer/login`, { waitUntil: 'networkidle2' });
        await fill(rev, 'input[name="email"]', REVIEWER.email);
        await fill(rev, 'input[name="password"]', REVIEWER.password);
        await Promise.all([
            rev.waitForNavigation({ waitUntil: 'networkidle2', timeout: 15000 }),
            rev.click('button[type="submit"]')
        ]);
        log('Reviewer login', rev.url().includes('/reviewer'), rev.url());
        await shot(rev, 'rev_01_dashboard');

        await rev.goto(`${BASE_URL}/reviewer/applications`, { waitUntil: 'networkidle2' });
        await sleep(800);
        await shot(rev, 'rev_02_applications_list');
        log('Reviewer applications list', !rev.url().includes('error'));

        // View the assigned application
        await rev.goto(`${BASE_URL}/reviewer/applications/${EXISTING_APP_ID}`, { waitUntil: 'networkidle2' });
        await sleep(1000);
        const revAppUrl = rev.url();
        await shot(rev, 'rev_03_application_detail');
        const canView = !revAppUrl.includes('403') && !revAppUrl.includes('error') && revAppUrl.includes(String(EXISTING_APP_ID));
        log('Reviewer can view assigned application', canView, revAppUrl);

        // Submit review
        if (canView) {
            try {
                const notesField = await rev.$('textarea[name="reviewer_notes"], textarea[name="notes"], #reviewer_notes');
                if (notesField) {
                    await notesField.click({ clickCount: 3 });
                    await notesField.type('Application reviewed — all documents verified. Candidate is eligible.');
                }

                // Find status submit button (look for approved/recommend etc)
                const statusBtn = await rev.evaluate(() => {
                    const btns = document.querySelectorAll('button[type="submit"], button[name="action"], button[name="status"]');
                    for (const b of btns) {
                        if (b.value === 'approved' || b.value === 'reviewed' || b.value === 'recommended') return b.value;
                    }
                    return null;
                });
                console.log('  Reviewer submit button value found:', statusBtn);

                const submitBtnEl = await rev.$(`button[value="${statusBtn}"]`) ||
                                    await rev.$('button[type="submit"]:not([name="draft"])');

                if (submitBtnEl) {
                    // Get the form and submit it via JS to avoid navigation issues
                    const csrfRev = await rev.evaluate(() => document.querySelector('meta[name="csrf-token"]')?.content || '');
                    const reviewRes = await rev.evaluate(async (appId, csrf, base, status) => {
                        const form = document.querySelector(`form[action*="/reviewer/applications/${appId}"]`);
                        if (!form) return { error: 'form not found' };
                        const action = form.action;
                        const method = form.querySelector('[name="_method"]')?.value || 'POST';
                        const fd = new FormData(form);
                        fd.set('status', status || 'reviewed');
                        const r = await fetch(action, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json, text/html, */*' },
                            body: fd
                        });
                        return { status: r.status, url: r.url };
                    }, EXISTING_APP_ID, csrfRev, BASE_URL, statusBtn);

                    log('Reviewer submit review', reviewRes.status === 200 || reviewRes.status === 302,
                        `HTTP ${reviewRes.status}`);
                    await sleep(500);
                    await shot(rev, 'rev_04_review_submitted');
                } else {
                    log('Reviewer submit review', null, 'submit button not found');
                }
            } catch(e) {
                log('Reviewer submit review', false, e.message);
            }
        }

        // ════════════════════════════════════════════════════════════
        //  APPROVER PORTAL
        // ════════════════════════════════════════════════════════════
        console.log('\n══════════════════════════════════════════════════════');
        console.log('  PHASE 4 — APPROVER PORTAL');
        console.log('══════════════════════════════════════════════════════');

        const appr = await browser.newPage();
        appr.setDefaultTimeout(12000);

        await appr.goto(`${BASE_URL}/approver/login`, { waitUntil: 'networkidle2' });
        await shot(appr, 'appr_00_login_page');

        // Approver uses employee_id not email
        await fill(appr, 'input[name="employee_id"]', APPROVER.employee_id);
        await fill(appr, 'input[name="password"]', APPROVER.password);
        await Promise.all([
            appr.waitForNavigation({ waitUntil: 'networkidle2', timeout: 15000 }),
            appr.click('button[type="submit"]')
        ]);
        log('Approver login', appr.url().includes('/approver') && !appr.url().includes('login'), appr.url());
        await shot(appr, 'appr_01_dashboard');

        await appr.goto(`${BASE_URL}/approver/assigned-to-me`, { waitUntil: 'networkidle2' });
        await sleep(800);
        await shot(appr, 'appr_02_assigned_list');
        log('Approver assigned list loads', !appr.url().includes('error'));

        // View the assigned application
        await appr.goto(`${BASE_URL}/approver/applications/${EXISTING_APP_ID}`, { waitUntil: 'networkidle2' });
        await sleep(1000);
        const apprAppUrl = appr.url();
        await shot(appr, 'appr_03_application_detail');
        const apprCanView = !apprAppUrl.includes('403') && !apprAppUrl.includes('error');
        log('Approver can view assigned application', apprCanView, apprAppUrl);

        // Submit approval
        if (apprCanView) {
            try {
                const notesField2 = await appr.$('textarea[name="approver_notes"], textarea[name="notes"], #approver_notes');
                if (notesField2) {
                    await notesField2.click({ clickCount: 3 });
                    await notesField2.type('Approved — candidate meets all criteria.');
                }

                const apprStatus = await appr.evaluate(() => {
                    const btns = document.querySelectorAll('button[type="submit"], button[name="action"], button[name="status"]');
                    for (const b of btns) {
                        if (b.value === 'approved' || b.value === 'shortlisted' || b.value === 'selected') return b.value;
                    }
                    return null;
                });
                console.log('  Approver decision button value:', apprStatus);

                const csrfAppr = await appr.evaluate(() => document.querySelector('meta[name="csrf-token"]')?.content || '');
                const decisionRes = await appr.evaluate(async (appId, csrf, base, status) => {
                    const form = document.querySelector(`form[action*="/approver/applications/${appId}"]`);
                    if (!form) return { error: 'form not found' };
                    const fd = new FormData(form);
                    fd.set('status', status || 'approved');
                    const r = await fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json, text/html, */*' },
                        body: fd
                    });
                    return { status: r.status };
                }, EXISTING_APP_ID, csrfAppr, BASE_URL, apprStatus);

                log('Approver submit decision', decisionRes.status === 200 || decisionRes.status === 302,
                    `HTTP ${decisionRes.status}`);
                await shot(appr, 'appr_04_decision_submitted');
            } catch(e) {
                log('Approver submit decision', false, e.message);
            }
        }

        // ════════════════════════════════════════════════════════════
        //  FINAL SUMMARY
        // ════════════════════════════════════════════════════════════
        console.log('\n');
        console.log('═'.repeat(60));
        console.log('                WORKFLOW TEST RESULTS');
        console.log('═'.repeat(60));
        let passed = 0, failed = 0, partial = 0;
        for (const r of results) {
            const icon = r.ok === true ? '✅' : r.ok === false ? '❌' : '⚠️ ';
            if (r.ok === true) passed++;
            else if (r.ok === false) failed++;
            else partial++;
        }
        console.log(`  PASSED: ${passed}  |  FAILED: ${failed}  |  PARTIAL: ${partial}`);
        console.log('═'.repeat(60));
        console.log(`\n📸 Screenshots: ${SCREENSHOTS_DIR}  (${idx} total)`);
        console.log('\n⏳ Browser open 40s — inspect any tab...\n');
        await sleep(40000);

    } catch(err) {
        console.error('\n❌ FATAL:', err.message);
        await sleep(60000);
    } finally {
        await browser.close();
    }
}

main();
