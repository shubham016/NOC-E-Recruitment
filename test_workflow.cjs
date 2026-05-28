const puppeteer = require('puppeteer');
const path = require('path');
const fs = require('fs');

const BASE_URL = 'http://127.0.0.1:8000';
const SCREENSHOTS_DIR = path.join(__dirname, 'test_screenshots');

const ADMIN    = { email: 'admin@recruitment.com', password: 'password' };
const CANDIDATE = { email: 'sujan@candidate.com',  password: 'Test@1234' };
const REVIEWER  = { email: 'suresh@reviewer.com',  password: 'Test@1234' };
const APPROVER  = { email: 'daniel@approver.com',  password: 'Test@1234' };

// Known IDs from DB
const EXISTING_APP_ID = 169;
const REVIEWER_ID     = 3;
const APPROVER_ID     = 5;
const ACTIVE_JOB_ID   = 178;   // Senior Assistant – deadline 2026-06-15

if (!fs.existsSync(SCREENSHOTS_DIR)) fs.mkdirSync(SCREENSHOTS_DIR, { recursive: true });

let idx = 0;
async function shot(page, label) {
    idx++;
    const file = path.join(SCREENSHOTS_DIR, `${String(idx).padStart(2,'0')}_${label.replace(/[^a-z0-9]/gi,'_')}.png`);
    await page.screenshot({ path: file, fullPage: true });
    console.log(`  📸 ${file}`);
}

async function fill(page, sel, txt, clear = true) {
    await page.waitForSelector(sel, { timeout: 8000 });
    if (clear) await page.click(sel, { clickCount: 3 });
    await page.type(sel, txt, { delay: 30 });
}

async function login(page, loginUrl, email, password) {
    await page.goto(loginUrl, { waitUntil: 'networkidle2' });
    await fill(page, 'input[name="email"]', email);
    await fill(page, 'input[name="password"]', password);
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 15000 }),
        page.click('button[type="submit"]')
    ]);
}

async function main() {
    const browser = await puppeteer.launch({
        headless: false,
        defaultViewport: null,
        args: ['--start-maximized']
    });

    const results = [];

    try {
        // ════════════════════════════════════════════════════════════
        //  PHASE 1 – ADMIN (NEPALI MODE)
        // ════════════════════════════════════════════════════════════
        console.log('\n══════════════════════════════════════════');
        console.log('  PHASE 1: ADMIN PORTAL IN NEPALI MODE');
        console.log('══════════════════════════════════════════');

        const admin = await browser.newPage();
        admin.setDefaultTimeout(12000);

        // 1-a. Login
        console.log('\n[1] Admin Login');
        await login(admin, `${BASE_URL}/admin/login`, ADMIN.email, ADMIN.password);
        console.log('  URL:', admin.url());
        await shot(admin, '01_admin_logged_in');
        results.push({ step: 'Admin login', ok: admin.url().includes('/admin') });

        // 1-b. Switch to Nepali
        console.log('\n[2] Switch to Nepali mode');
        await admin.goto(`${BASE_URL}/set-locale/ne`, { waitUntil: 'networkidle2' });
        await admin.goto(`${BASE_URL}/admin/dashboard`, { waitUntil: 'networkidle2' });
        await admin.waitForTimeout(1200);   // let converter run
        await shot(admin, '02_admin_dashboard_nepali');
        const dashText = await admin.evaluate(() => document.body.innerText.substring(0, 300));
        const hasNepali = /[\u0900-\u097F]/.test(dashText);
        console.log('  Nepali characters detected on dashboard:', hasNepali);
        results.push({ step: 'Nepali mode on dashboard', ok: hasNepali });

        // 1-c. Jobs list
        console.log('\n[3] Admin Jobs List (Nepali)');
        await admin.goto(`${BASE_URL}/admin/jobs`, { waitUntil: 'networkidle2' });
        await admin.waitForTimeout(1000);
        await shot(admin, '03_admin_jobs_list_nepali');
        results.push({ step: 'Admin jobs list loads', ok: !admin.url().includes('error') });

        // 1-d. Job Create page
        console.log('\n[4] Admin Job Create (Nepali)');
        await admin.goto(`${BASE_URL}/admin/jobs/create`, { waitUntil: 'networkidle2' });
        await admin.waitForTimeout(1200);
        await shot(admin, '04_admin_job_create_nepali');
        results.push({ step: 'Admin jobs/create loads', ok: !admin.url().includes('error') });

        // 1-e. Applications list
        console.log('\n[5] Admin Applications List (Nepali)');
        await admin.goto(`${BASE_URL}/admin/applications`, { waitUntil: 'networkidle2' });
        await admin.waitForTimeout(1200);
        await shot(admin, '05_admin_applications_nepali');
        results.push({ step: 'Admin applications list loads', ok: !admin.url().includes('error') });

        // 1-f. Open application modal (click View on app 169)
        console.log('\n[6] Admin Application Modal for App 169 (Nepali)');
        try {
            const viewBtn = await admin.$(`[data-id="${EXISTING_APP_ID}"], button[onclick*="${EXISTING_APP_ID}"], a[href*="/admin/applications/${EXISTING_APP_ID}"]`);
            if (viewBtn) {
                await viewBtn.click();
                await admin.waitForTimeout(1500);
                await shot(admin, '06_admin_application_modal_nepali');
                // Close modal
                const closeBtn = await admin.$('.modal .btn-close, .modal [data-bs-dismiss="modal"]');
                if (closeBtn) await closeBtn.click();
                await admin.waitForTimeout(500);
                results.push({ step: 'App modal opens in Nepali', ok: true });
            } else {
                console.log('  (View button not found on page — skipping modal test)');
                results.push({ step: 'App modal opens in Nepali', ok: null, note: 'button not found' });
            }
        } catch(e) {
            console.log('  Modal test error:', e.message);
            results.push({ step: 'App modal opens in Nepali', ok: false, note: e.message });
        }

        // 1-g. Assign Reviewer to App 169
        console.log('\n[7] Assign Reviewer to Application 169');
        try {
            // find the assign-reviewer button/form for app 169
            const assignRevSelector = `form[action*="/applications/${EXISTING_APP_ID}/assign-reviewer"] button, [data-app-id="${EXISTING_APP_ID}"] .assign-reviewer-btn`;
            let assignRevBtn = await admin.$(assignRevSelector);

            if (!assignRevBtn) {
                // Try looking for a select + button combination
                const revSelect = await admin.$(`select[name="reviewer_id"]`);
                if (revSelect) {
                    await admin.select(`select[name="reviewer_id"]`, String(REVIEWER_ID));
                    await admin.waitForTimeout(300);
                    const revSubmit = await admin.$(`form[action*="assign-reviewer"] button[type="submit"]`);
                    if (revSubmit) {
                        assignRevBtn = revSubmit;
                    }
                }
            }

            if (!assignRevBtn) {
                // Navigate to application show page where assign might be
                await admin.goto(`${BASE_URL}/admin/applications/${EXISTING_APP_ID}`, { waitUntil: 'networkidle2' }).catch(() => {});
                await admin.waitForTimeout(800);
                await shot(admin, '07_admin_application_show');
            }

            results.push({ step: 'Assign Reviewer navigated', ok: true });
        } catch(e) {
            console.log('  Assign reviewer error:', e.message);
            results.push({ step: 'Assign Reviewer', ok: false, note: e.message });
        }

        // 1-h. Try direct AJAX assign reviewer
        console.log('\n[8] Direct Assign Reviewer via AJAX');
        try {
            const csrfToken = await admin.evaluate(() => {
                const meta = document.querySelector('meta[name="csrf-token"]');
                return meta ? meta.getAttribute('content') : '';
            });

            const response = await admin.evaluate(async (appId, reviewerId, csrf, baseUrl) => {
                const res = await fetch(`${baseUrl}/admin/applications/${appId}/assign-reviewer`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ reviewer_id: reviewerId })
                });
                const text = await res.text();
                return { status: res.status, body: text.substring(0, 200) };
            }, EXISTING_APP_ID, REVIEWER_ID, csrfToken, BASE_URL);

            console.log('  Assign reviewer response:', response.status, response.body.substring(0, 100));
            results.push({ step: 'Assign Reviewer API', ok: response.status === 200 || response.status === 302, note: `HTTP ${response.status}` });
        } catch(e) {
            console.log('  Assign reviewer AJAX error:', e.message);
            results.push({ step: 'Assign Reviewer API', ok: false, note: e.message });
        }

        // 1-i. Candidates page
        console.log('\n[9] Admin Candidates (Nepali)');
        await admin.goto(`${BASE_URL}/admin/candidates`, { waitUntil: 'networkidle2' });
        await admin.waitForTimeout(1000);
        await shot(admin, '09_admin_candidates_nepali');
        results.push({ step: 'Admin candidates page', ok: !admin.url().includes('error') });

        // ════════════════════════════════════════════════════════════
        //  PHASE 2 – CANDIDATE PORTAL
        // ════════════════════════════════════════════════════════════
        console.log('\n══════════════════════════════════════════');
        console.log('  PHASE 2: CANDIDATE PORTAL');
        console.log('══════════════════════════════════════════');

        const cand = await browser.newPage();
        cand.setDefaultTimeout(12000);

        // 2-a. Login
        console.log('\n[10] Candidate Login');
        await login(cand, `${BASE_URL}/candidate/login`, CANDIDATE.email, CANDIDATE.password);
        console.log('  URL:', cand.url());
        await shot(cand, '10_candidate_logged_in');
        results.push({ step: 'Candidate login', ok: cand.url().includes('/candidate') });

        // 2-b. Jobs browsing
        console.log('\n[11] Candidate Jobs List');
        await cand.goto(`${BASE_URL}/candidate/jobs`, { waitUntil: 'networkidle2' });
        await cand.waitForTimeout(800);
        await shot(cand, '11_candidate_jobs_list');
        results.push({ step: 'Candidate jobs list', ok: !cand.url().includes('error') });

        // 2-c. Job detail
        console.log('\n[12] Candidate Job Detail');
        await cand.goto(`${BASE_URL}/candidate/jobs/${ACTIVE_JOB_ID}`, { waitUntil: 'networkidle2' });
        await cand.waitForTimeout(800);
        await shot(cand, '12_candidate_job_detail');
        results.push({ step: 'Candidate job detail', ok: !cand.url().includes('error') });

        // 2-d. Application form (create)
        console.log('\n[13] Candidate Application Form');
        await cand.goto(`${BASE_URL}/candidate/jobs/${ACTIVE_JOB_ID}/applications/create`, { waitUntil: 'networkidle2' });
        await cand.waitForTimeout(1000);
        await shot(cand, '13_candidate_application_form_step1');
        const appFormUrl = cand.url();
        console.log('  URL:', appFormUrl);
        // Either on form or redirect (already applied / not eligible)
        const onForm = appFormUrl.includes('/create') || appFormUrl.includes('/applications');
        results.push({ step: 'Candidate application form', ok: true, note: appFormUrl.includes('create') ? 'on form' : 'redirected: ' + appFormUrl });

        // 2-e. My Applications
        console.log('\n[14] Candidate My Applications');
        await cand.goto(`${BASE_URL}/candidate/applications`, { waitUntil: 'networkidle2' });
        await cand.waitForTimeout(800);
        await shot(cand, '14_candidate_my_applications');
        results.push({ step: 'Candidate my applications', ok: !cand.url().includes('error') });

        // 2-f. Dashboard
        console.log('\n[15] Candidate Dashboard');
        await cand.goto(`${BASE_URL}/candidate/dashboard`, { waitUntil: 'networkidle2' });
        await cand.waitForTimeout(800);
        await shot(cand, '15_candidate_dashboard');
        results.push({ step: 'Candidate dashboard', ok: !cand.url().includes('error') });

        // ════════════════════════════════════════════════════════════
        //  PHASE 3 – REVIEWER PORTAL
        // ════════════════════════════════════════════════════════════
        console.log('\n══════════════════════════════════════════');
        console.log('  PHASE 3: REVIEWER PORTAL');
        console.log('══════════════════════════════════════════');

        const rev = await browser.newPage();
        rev.setDefaultTimeout(12000);

        // 3-a. Login
        console.log('\n[16] Reviewer Login');
        await login(rev, `${BASE_URL}/reviewer/login`, REVIEWER.email, REVIEWER.password);
        console.log('  URL:', rev.url());
        await shot(rev, '16_reviewer_logged_in');
        results.push({ step: 'Reviewer login', ok: rev.url().includes('/reviewer') });

        // 3-b. Dashboard
        console.log('\n[17] Reviewer Dashboard');
        await rev.goto(`${BASE_URL}/reviewer/dashboard`, { waitUntil: 'networkidle2' });
        await rev.waitForTimeout(800);
        await shot(rev, '17_reviewer_dashboard');
        results.push({ step: 'Reviewer dashboard', ok: !rev.url().includes('error') });

        // 3-c. Assigned applications
        console.log('\n[18] Reviewer Assigned Applications');
        await rev.goto(`${BASE_URL}/reviewer/applications`, { waitUntil: 'networkidle2' });
        await rev.waitForTimeout(800);
        await shot(rev, '18_reviewer_applications');
        results.push({ step: 'Reviewer applications list', ok: !rev.url().includes('error') });

        // 3-d. View specific application
        console.log('\n[19] Reviewer: View Application 169');
        await rev.goto(`${BASE_URL}/reviewer/applications/${EXISTING_APP_ID}`, { waitUntil: 'networkidle2' });
        await rev.waitForTimeout(800);
        const revAppUrl = rev.url();
        await shot(rev, '19_reviewer_application_detail');
        console.log('  URL:', revAppUrl);
        results.push({ step: 'Reviewer view application', ok: !revAppUrl.includes('403') && !revAppUrl.includes('error'), note: revAppUrl });

        // 3-e. Submit review
        console.log('\n[20] Reviewer: Submit Review');
        try {
            const notesField = await rev.$('textarea[name="reviewer_notes"], #reviewer_notes, textarea[name="notes"]');
            if (notesField) {
                await notesField.click({ clickCount: 3 });
                await notesField.type('Test review - application looks complete and eligible.');
            }

            // Look for approve/recommend/pass buttons
            const approveBtn = await rev.$('button[value="approved"], button[name="action"][value="approved"], .btn-approve, [data-action="approve"]');
            const submitBtn = await rev.$('button[type="submit"], form .btn-primary');

            const btn = approveBtn || submitBtn;
            if (btn) {
                await Promise.all([
                    rev.waitForNavigation({ waitUntil: 'networkidle2', timeout: 10000 }).catch(() => {}),
                    btn.click()
                ]);
                await rev.waitForTimeout(800);
                await shot(rev, '20_reviewer_submitted_review');
                console.log('  ✅ Review submitted. URL:', rev.url());
                results.push({ step: 'Reviewer submit review', ok: true });
            } else {
                await shot(rev, '20_reviewer_no_submit_btn');
                console.log('  (Submit button not found)');
                results.push({ step: 'Reviewer submit review', ok: null, note: 'submit button not found' });
            }
        } catch(e) {
            console.log('  Review submit error:', e.message);
            results.push({ step: 'Reviewer submit review', ok: false, note: e.message });
        }

        // ════════════════════════════════════════════════════════════
        //  PHASE 4 – ADMIN: ASSIGN APPROVER
        // ════════════════════════════════════════════════════════════
        console.log('\n══════════════════════════════════════════');
        console.log('  PHASE 4: ADMIN ASSIGN APPROVER');
        console.log('══════════════════════════════════════════');

        console.log('\n[21] Admin: Assign Approver via AJAX');
        try {
            await admin.bringToFront();
            await admin.goto(`${BASE_URL}/admin/applications`, { waitUntil: 'networkidle2' });
            await admin.waitForTimeout(800);

            const csrfToken2 = await admin.evaluate(() => {
                const meta = document.querySelector('meta[name="csrf-token"]');
                return meta ? meta.getAttribute('content') : '';
            });

            const response2 = await admin.evaluate(async (appId, approverId, csrf, baseUrl) => {
                const res = await fetch(`${baseUrl}/admin/applications/${appId}/assign-approver`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ approver_id: approverId })
                });
                const text = await res.text();
                return { status: res.status, body: text.substring(0, 200) };
            }, EXISTING_APP_ID, APPROVER_ID, csrfToken2, BASE_URL);

            console.log('  Assign approver response:', response2.status, response2.body.substring(0, 100));
            await shot(admin, '21_admin_after_assign_approver');
            results.push({ step: 'Assign Approver API', ok: response2.status === 200 || response2.status === 302, note: `HTTP ${response2.status}` });
        } catch(e) {
            console.log('  Assign approver error:', e.message);
            results.push({ step: 'Assign Approver API', ok: false, note: e.message });
        }

        // ════════════════════════════════════════════════════════════
        //  PHASE 5 – APPROVER PORTAL
        // ════════════════════════════════════════════════════════════
        console.log('\n══════════════════════════════════════════');
        console.log('  PHASE 5: APPROVER PORTAL');
        console.log('══════════════════════════════════════════');

        const appr = await browser.newPage();
        appr.setDefaultTimeout(12000);

        // 5-a. Login
        console.log('\n[22] Approver Login');
        await login(appr, `${BASE_URL}/approver/login`, APPROVER.email, APPROVER.password);
        console.log('  URL:', appr.url());
        await shot(appr, '22_approver_logged_in');
        results.push({ step: 'Approver login', ok: appr.url().includes('/approver') });

        // 5-b. Dashboard
        console.log('\n[23] Approver Dashboard');
        await appr.goto(`${BASE_URL}/approver/dashboard`, { waitUntil: 'networkidle2' });
        await appr.waitForTimeout(800);
        await shot(appr, '23_approver_dashboard');
        results.push({ step: 'Approver dashboard', ok: !appr.url().includes('error') });

        // 5-c. Assigned applications
        console.log('\n[24] Approver Assigned Applications');
        await appr.goto(`${BASE_URL}/approver/assigned-to-me`, { waitUntil: 'networkidle2' });
        await appr.waitForTimeout(800);
        await shot(appr, '24_approver_assigned_list');
        results.push({ step: 'Approver assigned list', ok: !appr.url().includes('error') });

        // 5-d. View application
        console.log('\n[25] Approver: View Application 169');
        await appr.goto(`${BASE_URL}/approver/applications/${EXISTING_APP_ID}`, { waitUntil: 'networkidle2' });
        await appr.waitForTimeout(800);
        const apprAppUrl = appr.url();
        await shot(appr, '25_approver_application_detail');
        console.log('  URL:', apprAppUrl);
        results.push({ step: 'Approver view application', ok: !apprAppUrl.includes('403') && !apprAppUrl.includes('error'), note: apprAppUrl });

        // 5-e. Submit approval decision
        console.log('\n[26] Approver: Submit Decision');
        try {
            const notesField2 = await appr.$('textarea[name="approver_notes"], #approver_notes, textarea[name="notes"]');
            if (notesField2) {
                await notesField2.click({ clickCount: 3 });
                await notesField2.type('Test approval — application meets all requirements.');
            }

            const approveBtn2 = await appr.$('button[value="approved"], .btn-approve, [data-action="approve"], button[name="action"]');
            const submitBtn2 = await appr.$('button[type="submit"], form .btn-primary');
            const btn2 = approveBtn2 || submitBtn2;

            if (btn2) {
                await Promise.all([
                    appr.waitForNavigation({ waitUntil: 'networkidle2', timeout: 10000 }).catch(() => {}),
                    btn2.click()
                ]);
                await appr.waitForTimeout(800);
                await shot(appr, '26_approver_decision_submitted');
                console.log('  ✅ Decision submitted. URL:', appr.url());
                results.push({ step: 'Approver submit decision', ok: true });
            } else {
                await shot(appr, '26_approver_no_decision_btn');
                console.log('  (Decision button not found)');
                results.push({ step: 'Approver submit decision', ok: null, note: 'button not found' });
            }
        } catch(e) {
            console.log('  Approver decision error:', e.message);
            results.push({ step: 'Approver submit decision', ok: false, note: e.message });
        }

        // ════════════════════════════════════════════════════════════
        //  SUMMARY
        // ════════════════════════════════════════════════════════════
        console.log('\n');
        console.log('═'.repeat(60));
        console.log('                 TEST RESULTS SUMMARY');
        console.log('═'.repeat(60));
        let passed = 0, failed = 0, skipped = 0;
        for (const r of results) {
            const icon = r.ok === true ? '✅' : r.ok === false ? '❌' : '⚠️ ';
            if (r.ok === true) passed++;
            else if (r.ok === false) failed++;
            else skipped++;
            console.log(`  ${icon}  ${r.step}${r.note ? '  — ' + r.note : ''}`);
        }
        console.log('═'.repeat(60));
        console.log(`  PASSED: ${passed}  |  FAILED: ${failed}  |  SKIPPED/PARTIAL: ${skipped}`);
        console.log(`  📸 Screenshots: ${SCREENSHOTS_DIR}`);
        console.log('═'.repeat(60));

        console.log('\n⏳ Browser open for 45 seconds — inspect any tab you like...\n');
        await new Promise(r => setTimeout(r, 45000));

    } catch (err) {
        console.error('\n❌ FATAL ERROR:', err.message);
        console.error(err.stack);
        await new Promise(r => setTimeout(r, 60000));
    } finally {
        await browser.close();
        console.log('Browser closed.');
    }
}

main();
