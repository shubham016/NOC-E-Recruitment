/**
 * Admin Portal – Nepali / English Language Switcher  (v4 – full coverage)
 *
 * - Text nodes, placeholder, title, aria-label attributes
 * - ~400+ dictionary entries covering ALL admin pages
 * - MutationObserver for modals/AJAX
 * - document.title translation
 * - Word-boundary-safe greedy replacement for short keys
 */
(function () {
    'use strict';

    /* ================================================================== *
     * DICTIONARY  (English → Nepali)  — ~400 entries
     * ================================================================== */
    var D = {

        /* ── Company / header ────────────────────────────────────────── */
        'NEPAL OIL CORPORATION LTD.':                   'नेपाल आयल कर्पोरेशन लि.',
        'Nepal Oil Corporation Limited':                'नेपाल आयल कर्पोरेशन लिमिटेड',
        'Nepal Oil Corporation':                        'नेपाल आयल कर्पोरेशन',
        'Babarmahal, Kathmandu':                        'बाबरमहल, काठमाडौं',
        'Online Recruitment Management System':         'अनलाइन भर्ती व्यवस्थापन प्रणाली',
        'Nepal Oil Corporation - E-Recruitment System': 'नेपाल आयल कर्पोरेशन - इ-भर्ती प्रणाली',
        'Government of Nepal':                          'नेपाल सरकार',

        /* ── Sidebar ─────────────────────────────────────────────────── */
        'Admin Portal':    'एडमिन पोर्टल',
        'ADMIN PORTAL':    'एडमिन पोर्टल',
        'Dashboard':       'ड्यासबोर्ड',
        'Post Vacancy':    'रिक्त पद पोस्ट',
        'Vacancy List':    'रिक्त पद सूची',
        'Applications':    'आवेदनहरू',
        'Candidates':      'उम्मेदवारहरू',
        'Reviewers':       'समीक्षकहरू',
        'Approvers':       'अनुमोदकहरू',
        'Admit Cards':     'प्रवेशपत्रहरू',
        'Reports':         'प्रतिवेदनहरू',
        'Settings':        'सेटिङ्ग',

        /* ── User menu ───────────────────────────────────────────────── */
        'System Administrator': 'प्रणाली प्रशासक',
        'My Profile':          'मेरो प्रोफाइल',
        'Change Password':     'पासवर्ड परिवर्तन',
        'Log Out':             'लग आउट',
        'Notifications':       'सूचनाहरू',
        'Notification':        'सूचना',

        /* ── Dashboard ───────────────────────────────────────────────── */
        'Active Vacancies':          'सक्रिय रिक्त पद',
        'Pending Reviews':           'समीक्षा प्रतीक्षारत',
        'Pending Review':            'समीक्षा प्रतीक्षारत',
        'Active Reviewers':          'सक्रिय समीक्षकहरू',
        'Active Approvers':          'सक्रिय अनुमोदकहरू',
        'Recent Applications':       'हालका आवेदनहरू',
        'Applications per Vacancy':  'रिक्त पद अनुसार आवेदनहरू',
        'View All →':                'सबै हेर्नुहोस् →',
        'Applied on:':               'आवेदन मिति:',
        'Applied':                   'आवेदन',
        'No Recent Applications':    'हालसालै कुनै आवेदन छैन',
        'New applications will appear here': 'नयाँ आवेदनहरू यहाँ देखिनेछन्',
        'No Vacancies Posted':       'कुनै रिक्त पद पोस्ट भएको छैन',
        'Post your first vacancy':   'तपाईंको पहिलो रिक्त पद पोस्ट गर्नुहोस्',
        'Total Vacancies':           'कुल रिक्त पद',
        'Total Applications':        'कुल आवेदनहरू',
        'Total Candidates':          'कुल उम्मेदवारहरू',
        'Pending Applications':      'प्रतीक्षारत आवेदनहरू',
        'System Overview':           'प्रणाली सारांश',
        'System Load':               'प्रणाली लोड',
        'Storage':                   'भण्डारण',
        'Database':                  'डाटाबेस',

        /* ── Applications Management ─────────────────────────────────── */
        'Applications Management':   'आवेदन व्यवस्थापन',
        'Filter Applications':       'आवेदन फिल्टर',
        'Applications List':         'आवेदन सूची',
        'Candidate Information':     'उम्मेदवार जानकारी',
        'Vacancy Applied For':       'आवेदन गरिएको रिक्त पद',
        'Contact Details':           'सम्पर्क विवरण',
        'Application Date':          'आवेदन मिति',
        'Application Deadline':      'आवेदनको अन्तिम मिति',
        'Application Deadline:':     'आवेदनको अन्तिम मिति:',
        'Application Details':       'आवेदन विवरण',
        'Application Review':        'आवेदन समीक्षा',
        'Application Status History':'आवेदन स्थिति इतिहास',
        'Application Submmited:':    'आवेदन पेश गरिएको:',
        'Assigned Reviewer':         'तोकिएको समीक्षक',
        'Assigned Approver':         'तोकिएको अनुमोदक',
        'Assignment & Timeline':     'तोकपत्र र समयरेखा',
        'Vacancy Position':          'रिक्त पदको पद',
        'Bulk Actions':              'सामूहिक कार्यहरू',
        'Export to Excel':           'एक्सेलमा निर्यात',
        'Export to PDF':             'पिडिएफमा निर्यात',
        'Clear Selection':           'चयन हटाउनुहोस्',
        'application(s) selected':   'आवेदन(हरू) चयन गरिएको',
        'Application ID:':           'आवेदन आईडी:',
        'Application ID':            'आवेदन आईडी',
        'All Status':                'सबै स्थिति',
        'All Statuses':              'सबै स्थिति',
        'All Positions':             'सबै पदहरू',
        'All Reviewers':             'सबै समीक्षकहरू',
        'All Departments':           'सबै विभागहरू',
        'All Genders':               'सबै लिङ्ग',
        'All Vacancies':             'सबै रिक्त पद',
        'View Details':              'विवरण हेर्नुहोस्',
        'Update Application Status': 'आवेदन स्थिति अपडेट',
        'Select New Status':         'नयाँ स्थिति छान्नुहोस्',
        'New Status':                'नयाँ स्थिति',
        'Admin Notes (Optional)':    'एडमिन टिप्पणी (ऐच्छिक)',
        'Admin Notes':               'एडमिन टिप्पणी',
        'Update Status':             'स्थिति अपडेट',
        'Select Reviewer':           'समीक्षक छान्नुहोस्',
        'Select Approver':           'अनुमोदक छान्नुहोस्',
        'Select Action Type':        'कार्य प्रकार छान्नुहोस्',
        'Select Decision':           'निर्णय छान्नुहोस्',
        '-- Choose Reviewer --':     '-- समीक्षक छान्नुहोस् --',
        '-- Choose Approver --':     '-- अनुमोदक छान्नुहोस् --',
        '-- Choose Action --':       '-- कार्य छान्नुहोस् --',
        '-- Select Reviewer --':     '-- समीक्षक छान्नुहोस् --',
        '-- Select Approver --':     '-- अनुमोदक छान्नुहोस् --',
        'Assign Reviewer':           'समीक्षक तोक्नुहोस्',
        'Assign Approver':           'अनुमोदक तोक्नुहोस्',
        'Assign to Reviewer':        'समीक्षकलाई तोक्नुहोस्',
        'Assign to Approver':        'अनुमोदकलाई तोक्नुहोस्',
        'Assign by Advertisement Number': 'विज्ञापन नम्बर अनुसार तोक्नुहोस्',
        'Select an advertisement to assign': 'तोक्नको लागि विज्ञापन छान्नुहोस्',
        'No Applications Found':     'कुनै आवेदन फेला परेन',
        'No applications found for the selected filters.': 'चयन गरिएका फिल्टरहरूमा कुनै आवेदन फेला परेन।',
        'No applications match your current filter criteria.': 'तपाईंको हालको फिल्टर मापदण्ड अनुसार कुनै आवेदन मिलेन।',
        'Try adjusting your filters or search terms.': 'फिल्टर वा खोजी शब्दहरू परिवर्तन गर्नुहोस्।',
        'There are no job applications in the system yet.': 'प्रणालीमा अहिलेसम्म कुनै आवेदन छैन।',
        'Applications will appear here once candidates start applying.': 'उम्मेदवारहरूले आवेदन दिन सुरु गरेपछि आवेदनहरू यहाँ देखिनेछन्।',
        'Clear All Filters':         'सबै फिल्टर हटाउनुहोस्',
        'Validation Error:':         'प्रमाणीकरण त्रुटि:',
        'No Applications':           'कुनै आवेदन छैन',
        'No Applications Yet':       'अहिलेसम्म कुनै आवेदन छैन',
        'No reviewer assigned':      'कुनै समीक्षक तोकिएको छैन',
        'Decision':                  'निर्णय',
        'Reviewer Note:':            'समीक्षक टिप्पणी:',
        'Reviewed Date:':            'समीक्षा मिति:',
        'Done By':                   'द्वारा गरिएको',
        'Stage Name':                'चरण नाम',
        'Date & Time:':              'मिति र समय:',
        'Failed to load application details.': 'आवेदन विवरण लोड गर्न असफल भयो।',
        'Loading application details...': 'आवेदन विवरण लोड हुँदैछ...',
        'Update status':             'स्थिति अपडेट',
        'Assign reviewer':           'समीक्षक तोक्नुहोस्',
        'Assign approver':           'अनुमोदक तोक्नुहोस्',
        'Update_status':             'स्थिति अपडेट',
        'update_status':             'स्थिति अपडेट',
        'assign_reviewer':           'समीक्षक तोक्नुहोस्',
        'assign_approver':           'अनुमोदक तोक्नुहोस्',
        'Send Back for Edit':        'सम्पादनको लागि फिर्ता',

        /* ── Vacancy / Jobs ──────────────────────────────────────────── */
        'Vacancy Management':           'रिक्त पद व्यवस्थापन',
        'Create and manage Vacancies':  'रिक्त पदहरू सिर्जना र व्यवस्थापन',
        'Post New Vacancy':             'नयाँ रिक्त पद पोस्ट',
        'Total Vacancy':                'कुल रिक्त पद',
        'Active Vacancy':               'सक्रिय रिक्त पद',
        'Closed Vacancy':               'बन्द रिक्त पद',
        'Draft Vacancy':                'मस्यौदा रिक्त पद',
        'Notice No.':                   'सूचना नं.',
        'Advertisement No.':            'विज्ञापन नं.',
        'Advertisement No:':            'विज्ञापन नं:',
        'Adv. No.':                     'विज्ञापन नं.',
        'Position / Level':             'पद / तह',
        'Position/Level':               'पद/तह',
        'Service / Group':              'सेवा / समूह',
        'Service/Group':                'सेवा/समूह',
        'Demand':                       'माग',
        'Demand Post':                  'माग पद',
        'Demand Post (Number)':         'माग पद (संख्या)',
        'Qualifications':               'योग्यता',
        'Qualification':                'योग्यता',
        'Total Fee':                    'कुल शुल्क',
        'Total Application Fee':        'कुल आवेदन शुल्क',
        'Double Dastur':                'दोब्बर दस्तुर',
        'Double Dastur Date':           'दोब्बर दस्तुर मिति',
        'Double Dastur Fee':            'दोब्बर दस्तुर शुल्क',
        'Double Dastur Deadline':       'दोब्बर दस्तुर अन्तिम मिति',
        'Double Dastur (AD)':           'दोब्बर दस्तुर (AD)',
        'Double Dastur (BS)':           'दोब्बर दस्तुर (BS)',
        'Vacancy Title':                'रिक्त पद शीर्षक',
        'Vacancy Information':          'रिक्त पद जानकारी',
        'Vacancy Advertisement List':   'रिक्त पद विज्ञापन सूची',
        'Vacancy List Export':          'रिक्त पद सूची निर्यात',
        'Vacancy Posted':               'रिक्त पद पोस्ट भएको',
        'No Vacancy Found':             'कुनै रिक्त पद फेला परेन',
        'No active vacancies found.':   'कुनै सक्रिय रिक्त पद फेला परेन।',
        'No vacancies found for the selected filters.': 'चयन गरिएका फिल्टरमा कुनै रिक्त पद फेला परेन।',
        'Start by posting your first Vacancy!': 'तपाईंको पहिलो रिक्त पद पोस्ट गर्नुहोस्!',
        'Current Deadline:':            'हालको अन्तिम मिति:',
        'Extended Period:':             'विस्तारित अवधि:',
        'Age Limit':                    'उमेर सीमा',
        'Minimum Age':                  'न्यूनतम उमेर',
        'Maximum Age':                  'अधिकतम उमेर',
        'Minimum Educational Qualification': 'न्यूनतम शैक्षिक योग्यता',
        'Deadline (AD)':                'अन्तिम मिति (AD)',
        'Deadline (BS)':                'अन्तिम मिति (BS)',
        'Editing Vacancy:':             'रिक्त पद सम्पादन:',
        'Category / Type':             'वर्ग / प्रकार',
        'Inclusive Type':              'समावेशी प्रकार',
        'Internal Type':               'आन्तरिक प्रकार',
        'Open/Inclusive':              'खुला/समावेशी',
        'Open for all NOC employees':  'सबै एनओसी कर्मचारीहरूको लागि खुला',
        'For NOC employees only':      'एनओसी कर्मचारीहरूको लागि मात्र',
        'For NOC employees from inclusive categories': 'समावेशी वर्गका एनओसी कर्मचारीहरूको लागि',
        'Candidates can apply under any of these categories': 'उम्मेदवारहरूले यी मध्ये कुनै पनि वर्ग अन्तर्गत आवेदन दिन सक्छन्',
        'Important Notice:':           'महत्त्वपूर्ण सूचना:',
        'Priority Note':               'प्राथमिकता नोट',
        'Posts':                        'पदहरू',
        'NPR':                          'रु.',
        'Posted :':                     'पोस्ट गरिएको :',
        'Posted By':                    'पोस्ट गर्ने',
        'Posted On':                    'पोस्ट मिति',
        'Posted Date':                  'पोस्ट मिति',

        /* ── Candidates ──────────────────────────────────────────────── */
        'Manage Candidates':            'उम्मेदवार व्यवस्थापन',
        'Candidate Management':         'उम्मेदवार व्यवस्थापन',
        'View and manage all registered candidates': 'सबै दर्ता उम्मेदवारहरू हेर्नुहोस् र व्यवस्थापन गर्नुहोस्',
        'Candidates List':              'उम्मेदवार सूची',
        'With Applications':            'आवेदनसहित',
        'This Month':                   'यो महिना',
        'Registered':                   'दर्ता मिति',
        'Registered On':                'दर्ता मिति',
        'Registration Date':            'दर्ता मिति',
        'No Candidates Found':          'कुनै उम्मेदवार फेला परेन',
        'No candidates have registered yet.': 'अहिलेसम्म कुनै उम्मेदवार दर्ता भएका छैनन्।',
        'No candidates found for the selected filters.': 'चयन गरिएका फिल्टरमा कुनै उम्मेदवार फेला परेन।',
        'Candidate Name':               'उम्मेदवारको नाम',
        'Candidate ID':                 'उम्मेदवार आईडी',
        'Edit Candidate Profile':       'उम्मेदवार प्रोफाइल सम्पादन',
        'Update candidate information and account settings': 'उम्मेदवार जानकारी र खाता सेटिङ्ग अपडेट गर्नुहोस्',

        /* ── Personal Information ─────────────────────────────────────── */
        'Personal Information':         'व्यक्तिगत जानकारी',
        'First Name':                   'पहिलो नाम',
        'Middle Name':                  'बीचको नाम',
        'Last Name':                    'थर',
        'Name (English)':               'नाम (अंग्रेजी)',
        'Name (English):':              'नाम (अंग्रेजी):',
        'Name (Nepali)':                'नाम (नेपाली)',
        'Name (Nepali):':               'नाम (नेपाली):',
        'Date of Birth (AD)':           'जन्म मिति (AD)',
        'Date of Birth (BS)':           'जन्म मिति (BS)',
        'Birth Date (AD):':             'जन्म मिति (AD):',
        'Birth Date (BS):':             'जन्म मिति (BS):',
        'Blood Group:':                 'रक्त समूह:',
        'Marital Status':               'वैवाहिक स्थिति',
        'Marital Status:':              'वैवाहिक स्थिति:',
        'Nationality':                  'राष्ट्रियता',
        'Nationality:':                 'राष्ट्रियता:',
        'Religion':                     'धर्म',
        'Religion:':                    'धर्म:',
        'Mother Tongue:':               'मातृभाषा:',
        'Gender':                       'लिङ्ग',
        'Gender:':                      'लिङ्ग:',
        'Age':                          'उमेर',
        'Age:':                         'उमेर:',
        'DOB (AD)':                     'जन्म मिति (AD)',
        'DOB (BS)':                     'जन्म मिति (BS)',
        'Physical Disability:':         'शारीरिक अपाङ्गता:',
        'Disabled':                     'अपाङ्गता भएको',

        /* ── Address ─────────────────────────────────────────────────── */
        'Address Information':          'ठेगाना जानकारी',
        'Permanent Address':            'स्थायी ठेगाना',
        'Mailing Address':              'पत्राचार ठेगाना',
        'Mailing/Temporary Address':    'पत्राचार/अस्थायी ठेगाना',
        'Province:':                    'प्रदेश:',
        'District:':                    'जिल्ला:',
        'Municipality:':                'नगरपालिका:',
        'Ward No:':                     'वडा नं:',
        'Tole/Street:':                 'टोल/सडक:',
        'City':                         'शहर',
        'House Number:':                'घर नम्बर:',
        'Location':                     'स्थान',

        /* ── Family ──────────────────────────────────────────────────── */
        'Family Details':               'पारिवारिक विवरण',
        'Father':                       'बुबा',
        'Mother':                       'आमा',
        'Grandfather':                  'हजुरबुबा',
        'Spouse':                       'पति/पत्नी',
        'Spouse Name:':                 'पति/पत्नीको नाम:',
        'Spouse Nationality (If Married):': 'पति/पत्नीको राष्ट्रियता (विवाहित भएमा):',

        /* ── Citizenship ─────────────────────────────────────────────── */
        'Citizenship Information':      'नागरिकता जानकारी',
        'Citizenship Id':               'नागरिकता आईडी',
        'Citizenship No.':              'नागरिकता नं.',
        'Citizenship Number':           'नागरिकता नम्बर',
        'Citizenship Number:':          'नागरिकता नम्बर:',
        'Issue Date (AD):':             'जारी मिति (AD):',
        'Issue Date (BS):':             'जारी मिति (BS):',
        'Issue District':               'जारी जिल्ला',
        'Issue District:':              'जारी जिल्ला:',
        'Citizenship number cannot be changed': 'नागरिकता नम्बर परिवर्तन गर्न सकिँदैन',

        /* ── Community / Ethnic ───────────────────────────────────────── */
        'Community & Ethnic Information':'समुदाय र जातीय जानकारी',
        'Community':                     'समुदाय',
        'Community:':                    'समुदाय:',
        'Ethnic Group:':                 'जातीय समूह:',
        'Ethnic Certificate':            'जातीय प्रमाणपत्र',
        'Ethnic Certificate:':           'जातीय प्रमाणपत्र:',

        /* ── Education ───────────────────────────────────────────────── */
        'Educational Background':       'शैक्षिक पृष्ठभूमि',
        'Educational Certificates':     'शैक्षिक प्रमाणपत्रहरू',
        'Education Level':              'शिक्षा तह',
        'Education Level:':             'शिक्षा तह:',
        'Institution':                  'शिक्षण संस्था',
        'Institution Name':             'शिक्षण संस्थाको नाम',
        'Institution:':                 'शिक्षण संस्था:',
        'University':                   'विश्वविद्यालय',
        'Field of Study':               'अध्ययन क्षेत्र',
        'Field of Study:':              'अध्ययन क्षेत्र:',
        'Graduation Year':              'स्नातक वर्ष',
        'Graduation Year:':             'स्नातक वर्ष:',
        'Year of Graduation':           'स्नातक वर्ष',
        'Equivalency Certificate':      'समकक्षता प्रमाणपत्र',

        /* ── Experience ──────────────────────────────────────────────── */
        'Work Experience':              'कार्य अनुभव',
        'Organization Name':            'संस्थाको नाम',
        'Organization:':                'संस्था:',
        'Start Date:':                  'सुरु मिति:',
        'End Date:':                    'अन्त्य मिति:',
        'Has Work Experience:':         'कार्य अनुभव छ:',
        'Years:':                       'वर्ष:',

        /* ── Employment ──────────────────────────────────────────────── */
        'Employment & Disability Status': 'रोजगारी र अपाङ्गता स्थिति',
        'Employment Status:':           'रोजगारी स्थिति:',
        'NOC Employee':                 'एनओसी कर्मचारी',
        'NOC Employee:':                'एनओसी कर्मचारी:',
        'NOC ID Card':                  'एनओसी परिचयपत्र',
        'Employee Code':                'कर्मचारी कोड',
        'Employee ID':                  'कर्मचारी आईडी',

        /* ── Documents ───────────────────────────────────────────────── */
        'Uploaded Documents':           'अपलोड गरिएका कागजातहरू',
        'Passport Size Photo':          'पासपोर्ट साइज फोटो',
        'Signature':                    'हस्ताक्षर',
        'Character Certificate':        'चारित्रिक प्रमाणपत्र',
        'Disability Certificate':       'अपाङ्गता प्रमाणपत्र',
        'Disability Certificate:':      'अपाङ्गता प्रमाणपत्र:',
        'Cover Letter':                 'आवेदन पत्र',
        'Cover Letter (File)':          'आवेदन पत्र (फाइल)',
        'Other Documents':              'अन्य कागजातहरू',
        'View Document':                'कागजात हेर्नुहोस्',
        'Document:':                    'कागजात:',
        'Photo':                        'फोटो',
        'Profile Photo':                'प्रोफाइल फोटो',
        'Current Photo':                'हालको फोटो',
        'Change Photo':                 'फोटो परिवर्तन',
        'Upload Profile Photo':         'प्रोफाइल फोटो अपलोड',
        'Uploaded cover letter document':'अपलोड गरिएको आवेदन पत्र कागजात',

        /* ── Payment ─────────────────────────────────────────────────── */
        'Payment Information':          'भुक्तानी जानकारी',
        'Payment Gateway:':             'भुक्तानी गेटवे:',
        'Amount:':                      'रकम:',
        'Transcation ID:':              'कारोबार आईडी:',
        'Applied Category':             'आवेदन वर्ग',

        /* ── Reviewers ───────────────────────────────────────────────── */
        'Manage Reviewers':             'समीक्षक व्यवस्थापन',
        'Reviewer Management':          'समीक्षक व्यवस्थापन',
        'View and manage all application reviewers': 'सबै आवेदन समीक्षकहरू हेर्नुहोस् र व्यवस्थापन गर्नुहोस्',
        'Add New Reviewer':             'नयाँ समीक्षक थप्नुहोस्',
        'Total Reviewers':              'कुल समीक्षकहरू',
        'Inactive Reviewers':           'निष्क्रिय समीक्षकहरू',
        'Reviewers List':               'समीक्षक सूची',
        'Designation':                  'पदनाम',
        'Toggle Status':                'स्थिति परिवर्तन',
        'Change Reviewer Status':       'समीक्षक स्थिति परिवर्तन',
        'No Reviewers Found':           'कुनै समीक्षक फेला परेन',
        'No reviewers match your search criteria.': 'तपाईंको खोजी मापदण्ड अनुसार कुनै समीक्षक मिलेन।',
        'No reviewers found for the selected filters.': 'चयन गरिएका फिल्टरमा कुनै समीक्षक फेला परेन।',
        'Start by adding your first reviewer!': 'तपाईंको पहिलो समीक्षक थप्नुहोस्!',
        'Clear Filters':                'फिल्टर हटाउनुहोस्',
        'Edit Reviewer':                'समीक्षक सम्पादन',
        'Update reviewer information and settings': 'समीक्षक जानकारी र सेटिङ्ग अपडेट गर्नुहोस्',
        'Reset Reviewer Password':      'समीक्षक पासवर्ड रिसेट',
        'Reset Password':               'पासवर्ड रिसेट',
        'This Reviewer will have access to:': 'यो समीक्षकसँग पहुँच हुनेछ:',
        'Review application documents':  'आवेदन कागजात समीक्षा',
        'Add review comments':           'समीक्षा टिप्पणी थप्नुहोस्',
        'Approve/reject applications':   'आवेदन स्वीकृत/अस्वीकृत गर्नुहोस्',
        'View assigned applications':    'तोकिएका आवेदनहरू हेर्नुहोस्',
        'Add a new reviewer account to review and evaluate applications': 'आवेदनहरू समीक्षा र मूल्यांकन गर्न नयाँ समीक्षक खाता थप्नुहोस्',

        /* ── Approvers ───────────────────────────────────────────────── */
        'Approvers Management':         'अनुमोदक व्यवस्थापन',
        'Approver Management':          'अनुमोदक व्यवस्थापन',
        'Manage application approvers and their permissions': 'आवेदन अनुमोदकहरू र तिनीहरूका अनुमतिहरू व्यवस्थापन गर्नुहोस्',
        'Add New Approver':             'नयाँ अनुमोदक थप्नुहोस्',
        'Total Approvers':              'कुल अनुमोदकहरू',
        'Inactive Approvers':           'निष्क्रिय अनुमोदकहरू',
        'Approvers List':               'अनुमोदक सूची',
        'Assign Vacancy':               'रिक्त पद तोक्नुहोस्',
        'No approvers found':           'कुनै अनुमोदक फेला परेन',
        'No approvers found for the selected filters.': 'चयन गरिएका फिल्टरमा कुनै अनुमोदक फेला परेन।',
        'No Active Approvers':          'कुनै सक्रिय अनुमोदक छैन',
        'No Active Reviewers':          'कुनै सक्रिय समीक्षक छैन',
        'Edit Approver':                'अनुमोदक सम्पादन',
        'Update approver information':  'अनुमोदक जानकारी अपडेट गर्नुहोस्',
        'Create New Approver':          'नयाँ अनुमोदक सिर्जना',
        'Create New':                   'नयाँ सिर्जना',
        'Add a new approver to the system': 'प्रणालीमा नयाँ अनुमोदक थप्नुहोस्',
        'Add approvers to start':       'सुरु गर्न अनुमोदकहरू थप्नुहोस्',
        'Add reviewers to start':       'सुरु गर्न समीक्षकहरू थप्नुहोस्',

        /* ── Notifications ───────────────────────────────────────────── */
        'View and manage your notifications': 'तपाईंका सूचनाहरू हेर्नुहोस् र व्यवस्थापन गर्नुहोस्',
        'Mark All as Read':             'सबै पढिएको रूपमा चिन्ह लगाउनुहोस्',
        'Mark All as Seen':             'सबै देखिएको रूपमा चिन्ह लगाउनुहोस्',
        'Mark as Read':                 'पढिएको रूपमा चिन्ह लगाउनुहोस्',
        'Mark as Seen':                 'देखिएको रूपमा चिन्ह लगाउनुहोस्',
        'View Application':             'आवेदन हेर्नुहोस्',
        'No Notifications Available':   'कुनै सूचना उपलब्ध छैन',
        "You don't have any notifications at the moment.": 'तपाईंसँग अहिले कुनै सूचना छैन।',
        'Back to Dashboard':            'ड्यासबोर्डमा फर्कनुहोस्',
        'Unseen':                       'नदेखिएको',
        'Seen':                         'देखिएको',
        'New':                          'नयाँ',

        /* ── Admit Cards ─────────────────────────────────────────────── */
        'Admit Card Management':        'प्रवेशपत्र व्यवस्थापन',
        'Bulk assign exam details and roll numbers by vacancy.': 'रिक्त पद अनुसार परीक्षा विवरण र रोल नम्बरहरू एकैपटक तोक्नुहोस्।',
        'Vacancies with Approved Applications': 'स्वीकृत आवेदन भएका रिक्त पदहरू',
        'No approved applications available for admit card assignment.': 'प्रवेशपत्र तोक्नको लागि कुनै स्वीकृत आवेदन उपलब्ध छैन।',
        'Exam Date':                    'परीक्षा मिति',
        'Exam Details':                 'परीक्षा विवरण',
        'Exam Instructions':            'परीक्षा निर्देशनहरू',
        'Exam Venue (First Paper)':     'परीक्षा स्थल (पहिलो पत्र)',
        'Exam Venue (Second Paper)':    'परीक्षा स्थल (दोस्रो पत्र)',
        'First Paper Date / Time':      'पहिलो पत्र मिति / समय',
        'Second Paper Date / Time':     'दोस्रो पत्र मिति / समय',
        'Roll No.':                     'रोल नं.',
        'Roll Number Prefix':           'रोल नम्बर उपसर्ग',
        'Venue':                        'स्थान',
        'Assign Admit Cards':           'प्रवेशपत्र तोक्नुहोस्',
        'Re-assign':                    'पुन: तोक्नुहोस्',
        'Admit Cards Assigned':         'प्रवेशपत्र तोकिएको',
        'Assigned Admit Cards':         'तोकिएका प्रवेशपत्रहरू',
        'No admit cards assigned yet for this vacancy.': 'यो रिक्त पदको लागि अहिलेसम्म कुनै प्रवेशपत्र तोकिएको छैन।',

        /* ── Reports ─────────────────────────────────────────────────── */
        'Apply filters, preview the data, then download as PDF': 'फिल्टर लागू गर्नुहोस्, डेटा पूर्वावलोकन गर्नुहोस्, त्यसपछि PDF डाउनलोड गर्नुहोस्',
        'Applications Report':          'आवेदन प्रतिवेदन',
        'Registered Candidates Report': 'दर्ता उम्मेदवार प्रतिवेदन',
        'Vacancies Report':             'रिक्त पद प्रतिवेदन',
        'Reviewers Report':             'समीक्षक प्रतिवेदन',
        'Approvers Report':             'अनुमोदक प्रतिवेदन',
        'From Date':                    'मिति देखि',
        'To Date':                      'मिति सम्म',
        'Preview & Download':           'पूर्वावलोकन र डाउनलोड',
        'Download PDF':                 'PDF डाउनलोड',
        'Back to Reports':              'प्रतिवेदनमा फर्कनुहोस्',

        /* ── Settings / Profile ──────────────────────────────────────── */
        'System Settings':              'प्रणाली सेटिङ्ग',
        'Manage your account and preferences': 'तपाईंको खाता र प्राथमिकताहरू व्यवस्थापन गर्नुहोस्',
        'Profile Information':          'प्रोफाइल जानकारी',
        'Upload Photo':                 'फोटो अपलोड',
        'JPG or PNG, max 2 MB':         'JPG वा PNG, अधिकतम २ MB',
        'Full Name':                    'पूरा नाम',
        'Email Address':                'इमेल ठेगाना',
        'Phone Number':                 'फोन नम्बर',
        'Save Changes':                 'परिवर्तन सुरक्षित',
        'Current Password':             'हालको पासवर्ड',
        'New Password':                 'नयाँ पासवर्ड',
        'Confirm New Password':         'नयाँ पासवर्ड पुष्टि',
        'Confirm Password':             'पासवर्ड पुष्टि',
        'Password requirements:':       'पासवर्ड आवश्यकताहरू:',
        'Password Requirements':        'पासवर्ड आवश्यकताहरू',
        'At least 8 characters':        'कम्तीमा ८ अक्षर',
        'Mix of uppercase and lowercase letters': 'ठूलो र सानो अक्षरहरूको मिश्रण',
        'Include numbers and special characters': 'अंक र विशेष वर्णहरू समावेश गर्नुहोस्',
        'Update Password':              'पासवर्ड अपडेट',
        'Password Strength:':           'पासवर्ड बलियोपना:',
        'Min. 8 characters':            'न्यूनतम ८ अक्षर',
        'Minimum 8 characters long':    'न्यूनतम ८ अक्षर लामो',
        'Should contain uppercase and lowercase letters': 'ठूलो र सानो अक्षरहरू समावेश हुनुपर्छ',
        'Should include numbers and special characters': 'अंक र विशेष वर्णहरू समावेश हुनुपर्छ',
        'Password must be at least 8 characters long': 'पासवर्ड कम्तीमा ८ अक्षर लामो हुनुपर्छ',
        'Weak':                         'कमजोर',
        'Fair':                         'ठीकै',
        'Good':                         'राम्रो',
        'Strong':                       'बलियो',
        'View and manage your profile information': 'तपाईंको प्रोफाइल जानकारी हेर्नुहोस् र व्यवस्थापन गर्नुहोस्',
        'Edit Profile':                 'प्रोफाइल सम्पादन',
        'Contact Information':          'सम्पर्क जानकारी',
        'Not provided':                 'उपलब्ध छैन',
        'Update your account password': 'तपाईंको खाताको पासवर्ड अपडेट गर्नुहोस्',
        'Back to Profile':              'प्रोफाइलमा फर्कनुहोस्',
        'Change Your Password':         'तपाईंको पासवर्ड परिवर्तन गर्नुहोस्',
        'Job Management':               'रिक्त पद व्यवस्थापन',
        'Account Credentials':          'खाता प्रमाणपत्र',
        'Account Status':               'खाता स्थिति',
        'Member since:':                'सदस्य देखि:',
        'Created:':                     'सिर्जना गरिएको:',
        'Updated:':                     'अपडेट गरिएको:',
        'Last Updated':                 'अन्तिम अपडेट',

        /* ── Table headers (generic) ─────────────────────────────────── */
        'S.N.':                  'क्र.सं.',
        'S.N':                   'क्र.सं',
        'Sr. No.':               'क्र.सं.',
        'Name':                  'नाम',
        'Email':                 'इमेल',
        'Email:':                'इमेल:',
        'Phone':                 'फोन',
        'Mobile':                'मोबाइल',
        'Address':               'ठेगाना',
        'Status':                'स्थिति',
        'Status:':               'स्थिति:',
        'Actions':               'कार्यहरू',
        'Action':                'कार्य',
        'Date':                  'मिति',
        'Position':              'पद',
        'Position:':             'पद:',
        'Position Applied:':     'आवेदन गरिएको पद:',
        'Department':            'विभाग',
        'Department:':           'विभाग:',
        'Service Group':         'सेवा समूह',
        'Level':                 'तह',
        'Deadline':              'अन्तिम मिति',
        'Application Fee':       'आवेदन शुल्क',
        'Submitted At':          'पेश गरिएको मिति',
        'Submitted Date':        'पेश मिति',
        'Applied Date':          'आवेदन मिति',
        'Applied On':            'आवेदन मिति',
        'Applicant Name':        'आवेदकको नाम',
        'Applied For':           'आवेदन गरिएको पद',
        'Total':                 'कुल',
        'total':                 'कुल',
        'Category':              'वर्ग',
        'Type':                  'प्रकार',
        'Amount':                'रकम',
        'Payment':               'भुक्तानी',
        'Transaction':           'कारोबार',
        'Role':                  'भूमिका',
        'Description':           'विवरण',
        'Username':              'प्रयोगकर्ता नाम',
        'Password':              'पासवर्ड',
        'Profile':               'प्रोफाइल',
        'Admit Card':            'प्रवेशपत्र',
        'Report':                'प्रतिवेदन',
        'Setting':               'सेटिङ्ग',
        'Vacancy':               'रिक्त पद',
        'Application':           'आवेदन',
        'Candidate':             'उम्मेदवार',
        'Reviewer':              'समीक्षक',
        'Reviewer:':             'समीक्षक:',
        'Approver':              'अनुमोदक',
        'Experience':            'अनुभव',
        'Education':             'शिक्षा',
        'Assigned':              'तोकिएको',
        'Assigned to Me':        'मलाई तोकिएको',
        'Remarks':               'कैफियत',
        'Note:':                 'नोट:',
        'ID:':                   'आईडी:',
        'Joined':                'सामेल भएको',
        'State':                 'अवस्था',
        'Auto':                  'स्वचालित',
        'Total Applied':         'कुल आवेदन',
        'Total Assigned':        'कुल तोकिएको',
        'Total Actioned':        'कुल कारवाही',
        'Reviewed At':           'समीक्षा गरिएको',

        /* ── Status values ───────────────────────────────────────────── */
        'Active':          'सक्रिय',
        'Inactive':        'निष्क्रिय',
        'Pending':         'प्रतीक्षारत',
        'Approved':        'स्वीकृत',
        'Rejected':        'अस्वीकृत',
        'Shortlisted':     'छोटो सूचीमा',
        'Reviewed':        'समीक्षा गरिएको',
        'Under Review':    'समीक्षाधीन',
        'Under review':    'समीक्षाधीन',
        'Draft':           'मस्यौदा',
        'Edited':          'सम्पादित',
        'Closed':          'बन्द',
        'Completed':       'सम्पन्न',
        'Paid':            'भुक्तानी भएको',
        'Unpaid':          'भुक्तानी नभएको',
        'Not Paid':        'भुक्तानी भएको छैन',
        'Not Assigned':    'तोकिएको छैन',
        'Not set':         'सेट गरिएको छैन',
        'Open':            'खुला',
        'Inclusive':       'समावेशी',
        'Internal':        'आन्तरिक',
        'Internal Appraisal': 'आन्तरिक मूल्यांकन',
        'Submitted':       'पेश गरिएको',

        /* ── Buttons ─────────────────────────────────────────────────── */
        'View':            'हेर्नुहोस्',
        'Edit':            'सम्पादन',
        'Delete':          'मेटाउनुहोस्',
        'Save':            'सुरक्षित',
        'Cancel':          'रद्द',
        'Search':          'खोज्नुहोस्',
        'Clear':           'हटाउनुहोस्',
        'Filter':          'फिल्टर',
        'Reset':           'रिसेट',
        'Submit':          'पेश गर्नुहोस्',
        'Update':          'अपडेट',
        'Create':          'सिर्जना',
        'Add':             'थप्नुहोस्',
        'Back':            'फिर्ता',
        'Close':           'बन्द',
        'Show':            'हेर्नुहोस्',
        'Assign':          'तोक्नुहोस्',
        'Download':        'डाउनलोड',
        'Export':          'निर्यात',
        'Print':           'प्रिन्ट',
        'Preview':         'पूर्वावलोकन',
        'Preview:':        'पूर्वावलोकन:',
        'Generate':        'उत्पन्न गर्नुहोस्',
        'Approve':         'स्वीकृत गर्नुहोस्',
        'Reject':          'अस्वीकृत गर्नुहोस्',
        'Shortlist':       'छोटो सूचीमा राख्नुहोस्',
        'Send Back to Edit':'सम्पादनको लागि फिर्ता',
        'Send Back for Edit':'सम्पादनको लागि फिर्ता',
        'Activate':        'सक्रिय गर्नुहोस्',
        'Deactivate':      'निष्क्रिय गर्नुहोस्',
        'Edit Access':     'सम्पादन पहुँच',
        'PDF':             'PDF',

        /* ── Alerts ──────────────────────────────────────────────────── */
        'Success!':        'सफल!',
        'Error!':          'त्रुटि!',
        'Validation Error:': 'प्रमाणीकरण त्रुटि:',

        /* ── Common form labels ──────────────────────────────────────── */
        'Male':            'पुरुष',
        'Female':          'महिला',
        'Other':           'अन्य',
        'Yes':             'हो',
        'No':              'होइन',
        'N/A':             'लागू हुँदैन',

        /* ── Empty states ────────────────────────────────────────────── */
        'No records found.':     'कुनै अभिलेख फेला परेन।',
        'No records found':      'कुनै अभिलेख फेला परेन',
        'No data available':     'डेटा उपलब्ध छैन',
        'No history available yet.': 'अहिलेसम्म कुनै इतिहास उपलब्ध छैन।',
        'Not yet entered...':    'अहिलेसम्म भरिएको छैन...',
        'Loading...':            'लोड हुँदैछ...',
        'Converting...':         'रूपान्तरण हुँदैछ...',

        /* ── Pagination ──────────────────────────────────────────────── */
        'Previous':        'अघिल्लो',
        'Next':            'अर्को',
        'First':           'पहिलो',
        'Last':            'अन्तिम',
        'Showing':         'देखाइँदैछ',
        'to':              'देखि',
        'of':              'मध्ये',
        'entries':         'प्रविष्टिहरू',
        'per page':        'प्रति पृष्ठ',
        'applications':    'आवेदनहरू',
        'reviewed':        'समीक्षा गरिएको',
        'pending':         'प्रतीक्षारत',

        /* ── Footer ──────────────────────────────────────────────────── */
        'Copyright':       'सर्वाधिकार',

        /* ── Form misc (asterisk note) ───────────────────────────────── */
        'All fields marked with':  'चिन्ह लगाइएका सबै फिल्डहरू',
        'Leave blank to use checkbox selection instead.': 'चेकबक्स चयन प्रयोग गर्न खाली छोड्नुहोस्।',
        'System generated, cannot be changed': 'प्रणाली द्वारा उत्पन्न, परिवर्तन गर्न सकिँदैन',
        'Signature already uploaded. Upload new to replace.': 'हस्ताक्षर पहिले नै अपलोड भइसकेको छ। प्रतिस्थापनको लागि नयाँ अपलोड गर्नुहोस्।',
        'Accepts JPG, PNG, GIF. Displayed on candidate admit card.': 'JPG, PNG, GIF स्वीकार्य। उम्मेदवार प्रवेशपत्रमा देखाइनेछ।',
        'Additional supporting documents': 'अतिरिक्त सहायक कागजातहरू',
        'Academic transcripts and degrees': 'शैक्षिक प्रतिलिपि र उपाधिहरू',

        /* ── Placeholder texts ───────────────────────────────────────── */
        'Name, email, vacancy title...':              'नाम, इमेल, रिक्त पद शीर्षक...',
        'Name, email, adv. no., position...':         'नाम, इमेल, विज्ञापन नं., पद...',
        'Search by name, email, username, mobile...': 'नाम, इमेल, प्रयोगकर्ता नाम, मोबाइल... बाट खोज्नुहोस्',
        'Search by name, email, phone...':            'नाम, इमेल, फोन... बाट खोज्नुहोस्',
        'Name, Email, Employee ID...':                'नाम, इमेल, कर्मचारी आईडी...',
        'Name, email, username, mobile...':           'नाम, इमेल, प्रयोगकर्ता नाम, मोबाइल...',
        'Search Vacancies...':                        'रिक्त पद खोज्नुहोस्...',
        'Add your review notes here...':              'तपाईंको समीक्षा टिप्पणी यहाँ लेख्नुहोस्...',
        'Add your remarks here...':                   'तपाईंको कैफियत यहाँ लेख्नुहोस्...',
        'Name or email...':                           'नाम वा इमेल...',
        'Position, adv. no., service/group...':       'पद, विज्ञापन नं., सेवा/समूह...',
        'e.g. 98XXXXXXXX':                            'जस्तै ९८XXXXXXXX',
        'Search...':                                  'खोज्नुहोस्...',
        'Enter full name':                            'पूरा नाम लेख्नुहोस्',
        'Enter amount (NPR)':                         'रकम लेख्नुहोस् (रु.)',
        'Enter Double Dastur Fee':                    'दोब्बर दस्तुर शुल्क लेख्नुहोस्',
        'Enter number of posts':                      'पद संख्या लेख्नुहोस्',
        'Exam center name and address':               'परीक्षा केन्द्रको नाम र ठेगाना',
        'Instructions for candidates...':             'उम्मेदवारहरूका लागि निर्देशनहरू...',
        'Total Application Fees':                     'कुल आवेदन शुल्क',
        'Select a category above to enter fees':      'शुल्क भर्नको लागि माथि वर्ग छान्नुहोस्',
        'Select a category above to enter individual fees.': 'व्यक्तिगत शुल्क भर्नको लागि माथि वर्ग छान्नुहोस्।',
    };

    /* ── Sort keys longest-first for greedy matching ─────────────────── */
    var SORTED = Object.keys(D).sort(function (a, b) { return b.length - a.length; });

    /* Minimum key length for greedy (substring) replacement.
       Keys shorter than this ONLY match via exact (trimmed) lookup,
       preventing "to" from corrupting "Total", "Photo", etc. */
    var MIN_GREEDY_LEN = 4;

    /* ── Tags whose TEXT NODES we never touch ────────────────────────── */
    var SKIP = { script:1, style:1, input:1, textarea:1, pre:1, code:1, svg:1 };

    var KEY   = 'adminLang';
    var ORIG  = typeof WeakMap !== 'undefined' ? new WeakMap() : null;
    var ATTRS = typeof WeakMap !== 'undefined' ? new WeakMap() : null;

    /* ================================================================== */
    function getLang()  { try { return localStorage.getItem(KEY) || 'en'; } catch(e) { return 'en'; } }
    function saveLang(l){ try { localStorage.setItem(KEY, l); } catch(e){} }

    function isSwitcher(el) {
        if (!el) return false;
        if (el.id === 'adminLangSwitcher') return true;
        return el.closest && el.closest('#adminLangSwitcher');
    }

    /* Translate a string — exact match first, then safe greedy */
    function lookup(t) {
        var s = t.trim();
        if (!s) return null;
        /* exact */
        if (Object.prototype.hasOwnProperty.call(D, s)) return t.replace(s, D[s]);
        /* greedy — skip keys shorter than MIN_GREEDY_LEN */
        var out = t;
        for (var i = 0; i < SORTED.length; i++) {
            var k = SORTED[i];
            if (k.length < MIN_GREEDY_LEN) continue;
            if (out.indexOf(k) !== -1) out = out.split(k).join(D[k]);
        }
        return out !== t ? out : null;
    }

    /* ── Text-node translation ───────────────────────────────────────── */
    function nodeFilter(n) {
        var p = n.parentElement;
        if (!p) return 2;
        if (SKIP[p.tagName.toLowerCase()]) return 2;
        if (isSwitcher(p)) return 2;
        if (p.closest && p.closest('[data-no-translate]')) return 2;
        return 1;
    }

    function translateTextNodes(root, lang) {
        var w = document.createTreeWalker(root, 4, { acceptNode: nodeFilter });
        var nodes = [];
        while (w.nextNode()) nodes.push(w.currentNode);
        for (var i = 0; i < nodes.length; i++) {
            var n = nodes[i];
            if (ORIG && !ORIG.has(n)) ORIG.set(n, n.textContent);
            var orig = ORIG ? ORIG.get(n) : n.textContent;
            if (lang !== 'ne') { n.textContent = orig; continue; }
            var tr = lookup(orig);
            if (tr) n.textContent = tr;
        }
    }

    /* ── Attribute translation (placeholder, title, aria-label) ──────── */
    var AL = ['placeholder', 'title', 'aria-label'];

    function translateAttributes(root, lang) {
        var all;
        try { all = root.querySelectorAll('[placeholder],[title],[aria-label]'); } catch(e){ return; }
        for (var i = 0; i < all.length; i++) {
            var el = all[i];
            if (isSwitcher(el)) continue;
            if (el.closest && el.closest('[data-no-translate]')) continue;
            if (ATTRS && !ATTRS.has(el)) {
                var snap = {};
                for (var a = 0; a < AL.length; a++) {
                    if (el.hasAttribute(AL[a])) snap[AL[a]] = el.getAttribute(AL[a]);
                }
                ATTRS.set(el, snap);
            }
            var os = ATTRS ? ATTRS.get(el) : null;
            for (var a = 0; a < AL.length; a++) {
                var attr = AL[a], ov = os ? os[attr] : el.getAttribute(attr);
                if (!ov) continue;
                if (lang !== 'ne') { el.setAttribute(attr, ov); }
                else { var tr = lookup(ov); if (tr) el.setAttribute(attr, tr); }
            }
        }
    }

    /* ── Document title ──────────────────────────────────────────────── */
    var origTitle = null;
    function translateTitle(lang) {
        if (origTitle === null) origTitle = document.title;
        if (lang !== 'ne') { document.title = origTitle; return; }
        var tr = lookup(origTitle);
        if (tr) document.title = tr;
    }

    /* ── Full page ───────────────────────────────────────────────────── */
    function applyAll(lang) {
        translateTextNodes(document.body, lang);
        translateAttributes(document.body, lang);
        translateTitle(lang);
    }

    /* ── MutationObserver ────────────────────────────────────────────── */
    var debounce = null;
    function startObserver() {
        if (typeof MutationObserver === 'undefined') return;
        new MutationObserver(function (muts) {
            if (getLang() !== 'ne') return;
            clearTimeout(debounce);
            debounce = setTimeout(function () {
                for (var i = 0; i < muts.length; i++) {
                    var added = muts[i].addedNodes;
                    for (var j = 0; j < added.length; j++) {
                        var n = added[j];
                        if (n.nodeType === 1) {
                            translateTextNodes(n, 'ne');
                            translateAttributes(n, 'ne');
                        }
                    }
                }
            }, 60);
        }).observe(document.body, { childList: true, subtree: true });
    }

    /* ── Init ────────────────────────────────────────────────────────── */
    function init() {
        var sel = document.getElementById('adminLangSwitcher');
        if (sel) {
            sel.value = getLang();
            sel.addEventListener('change', function () {
                saveLang(this.value);
                applyAll(this.value);
            });
        }
        if (getLang() === 'ne') applyAll('ne');
        startObserver();
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();

    window.adminTranslator = {
        apply: applyAll, getLang: getLang,
        setLang: function (l) { saveLang(l); applyAll(l); }
    };
})();
