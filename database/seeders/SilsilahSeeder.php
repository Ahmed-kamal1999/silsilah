<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\Stat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class SilsilahSeeder extends Seeder
{
    private function t(string $ar, string $en): array
    {
        return ['ar' => $ar, 'en' => $en];
    }

    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@silsilah.example')],
            // TODO: confirm with client — change this password before going live
            ['name' => 'Silsilah Admin', 'password' => Hash::make(env('ADMIN_PASSWORD', 'password'))],
        );

        $this->services();
        $this->posts($admin);
        $this->logos();
        $this->stats();
        $this->pages();
        $this->settings();
    }

    private function services(): void
    {
        $groups = [
            ['financial-protection', $this->t('حلول حماية القطاع المالي', 'Financial Sector Protection Solutions'), [
                ['pentest', 'shield', 'اختبار اختراق الأنظمة المالية والبوابات', 'Penetration Testing for Financial Systems & Gateways',
                    'محاكاة هجمات واقعية على أنظمتك وبوابات الدفع لكشف الثغرات قبل استغلالها.',
                    'Realistic attack simulation against your systems and payment gateways to uncover weaknesses before they are exploited.'],
                ['payment-app-security', 'lock', 'تقييم أمن تطبيقات الدفع والمحافظ', 'Payment & Wallet App Security Assessment',
                    'فحص شامل لتطبيقات الجوال والويب الخاصة بالدفع والمحافظ الإلكترونية وفق OWASP MASVS.',
                    'Comprehensive testing of mobile and web payment and wallet apps against OWASP MASVS.'],
                ['api-security', 'api', 'تقييم أمن واجهات الـ API والتكاملات البنكية', 'API & Banking Integration Security Assessment',
                    'تقييم أمن واجهات البرمجة والتكاملات مع البنوك والجهات الخارجية، بما فيها المصادقة والتفويض وحماية البيانات.',
                    'Security assessment of APIs and integrations with banks and third parties, covering authentication, authorization and data protection.'],
                ['secure-code-review', 'code', 'مراجعة الشفرات البرمجية الآمنة', 'Secure Code Review',
                    'مراجعة يدوية وآلية للشفرة المصدرية لاكتشاف العيوب الأمنية مبكراً ضمن دورة التطوير.',
                    'Manual and automated source-code review to catch security flaws early in the development lifecycle.'],
                ['compliance-assessment', 'check', 'تقييم الامتثال لضوابط SAMA وNCA وPCI DSS', 'SAMA, NCA & PCI DSS Compliance Assessment',
                    'قياس الفجوات مقابل الأطر التنظيمية وإعداد خطة معالجة واضحة وقابلة للتنفيذ.',
                    'Gap analysis against regulatory frameworks, with a clear and actionable remediation roadmap.'],
                ['fraud-prevention', 'eye', 'مكافحة الاحتيال ومراقبة المعاملات', 'Fraud Prevention & Transaction Monitoring',
                    'تصميم قواعد وأنظمة رصد لحظية للمعاملات المشبوهة والحد من الخسائر الاحتيالية.',
                    'Design of real-time rules and monitoring systems that flag suspicious transactions and reduce fraud losses.'],
                ['data-protection-pdpl', 'database', 'حماية البيانات والخصوصية (PDPL)', 'Data Protection & Privacy (PDPL)',
                    'مواءمة ممارسات معالجة البيانات الشخصية مع نظام حماية البيانات الشخصية في المملكة.',
                    'Aligning personal-data handling practices with the Kingdom\'s Personal Data Protection Law.'],
                ['business-continuity', 'refresh', 'تقييم استمرارية الأعمال والتعافي', 'Business Continuity & Recovery Assessment',
                    'تحليل الأثر وخطط التعافي من الكوارث لضمان استمرار الخدمات المالية الحرجة.',
                    'Impact analysis and disaster-recovery planning to keep critical financial services running.'],
            ], [
                ['تحديد النطاق ونمذجة التهديدات', 'تنفيذ الفحص والتحليل', 'التقرير وتصنيف المخاطر', 'دعم المعالجة وإعادة الاختبار'],
                ['Scoping & threat modeling', 'Testing & analysis', 'Reporting & risk rating', 'Remediation support & retest'],
                ['تقرير تنفيذي للإدارة العليا', 'تقرير فني مفصل بالثغرات وأدلة الإثبات', 'خطة معالجة مرتبة حسب الأولوية', 'جلسة عرض النتائج وإعادة اختبار مجانية للثغرات المعالجة'],
                ['Executive summary for senior management', 'Detailed technical report with proof of concept', 'Prioritised remediation plan', 'Findings workshop and free retest of fixed issues'],
            ]],
            ['fintech-development', $this->t('حلول تطوير التقنية المالية', 'Fintech Development Solutions'), [
                ['payment-platforms', 'wallet', 'تطوير منصات الدفع والمحافظ الإلكترونية', 'Payment Platform & E-Wallet Development',
                    'بناء منصات دفع ومحافظ رقمية آمنة وقابلة للتوسع تدعم الأفراد والمنشآت.',
                    'Building secure, scalable payment platforms and digital wallets for individuals and businesses.'],
                ['payment-gateways', 'card', 'تكامل بوابات الدفع (Apple Pay، mada، Google Pay)', 'Payment Gateway Integration (Apple Pay, mada, Google Pay)',
                    'ربط منصتك بوسائل الدفع الشائعة في السعودية بتجربة سلسة وامتثال كامل لمعايير الأمان.',
                    'Connecting your platform to the most common Saudi payment methods with a smooth experience and full security compliance.'],
                ['crowdfunding-investment', 'chart', 'منصات التمويل الجماعي والاستثمار', 'Crowdfunding & Investment Platforms',
                    'تطوير منصات للتمويل الجماعي والاستثمار مع إدارة المستثمرين والمحافظ والتقارير.',
                    'Developing crowdfunding and investment platforms with investor, portfolio and reporting management.'],
                ['kyc-aml', 'id', 'أنظمة KYC/AML', 'KYC/AML Systems',
                    'أتمتة التحقق من الهوية وفحص قوائم العقوبات ومراقبة المعاملات لمكافحة غسل الأموال.',
                    'Automating identity verification, sanctions screening and transaction monitoring for anti-money-laundering.'],
                ['open-banking', 'api', 'Open Banking APIs', 'Open Banking APIs',
                    'تصميم وتنفيذ واجهات الخدمات المصرفية المفتوحة وفق المعايير المحلية.',
                    'Designing and implementing Open Banking APIs aligned with local standards.'],
                ['dashboards-reporting', 'dashboard', 'لوحات التحكم والتقارير المالية', 'Financial Dashboards & Reporting',
                    'لوحات تحليلية وتقارير لحظية تدعم اتخاذ القرار المالي والتشغيلي.',
                    'Analytical dashboards and live reports that support financial and operational decisions.'],
                ['digital-transformation', 'compass', 'الاستشارات التقنية والتحول الرقمي', 'Technical Consulting & Digital Transformation',
                    'استشارات معمارية واستراتيجية لتحديث الأنظمة المالية والانتقال إلى منصات رقمية حديثة.',
                    'Architecture and strategy consulting to modernise financial systems and move to modern digital platforms.'],
                ['bnpl-lending', 'coins', 'منصات التمويل والدفع الآجل', 'Lending & BNPL Platforms',
                    'أنظمة لإدارة دورة التمويل من التقييم الائتماني حتى التحصيل.',
                    'Systems that manage the full lending cycle from credit scoring to collection.'],
            ], [
                ['الاكتشاف وتحليل المتطلبات', 'التصميم والمعمارية', 'البناء والاختبار', 'الإطلاق والدعم'],
                ['Discovery & requirements analysis', 'Design & architecture', 'Build & test', 'Launch & support'],
                ['وثيقة المتطلبات والمعمارية', 'نموذج أولي قابل للتجربة', 'منصة جاهزة للإنتاج مع اختبارات آلية', 'وثائق تقنية وتدريب الفريق وفترة دعم بعد الإطلاق'],
                ['Requirements and architecture document', 'Interactive prototype', 'Production-ready platform with automated tests', 'Technical documentation, team training and post-launch support'],
            ]],
        ];

        foreach ($groups as $gi => [$slug, $name, $items, $tpl]) {
            $cat = ServiceCategory::updateOrCreate(['slug' => $slug], [
                'name' => $name, 'sort' => $gi + 1, 'is_active' => true,
                'description' => $this->t(
                    $gi === 0 ? 'نختبر ونقيّم ونحمي أنظمتك المالية قبل أن يصل إليها المهاجمون.' : 'نصمم ونبني منصات مالية رقمية آمنة وقابلة للتوسع.',
                    $gi === 0 ? 'We test, assess and protect your financial systems before attackers reach them.' : 'We design and build secure, scalable digital financial platforms.',
                ),
            ]);

            foreach ($items as $i => [$sslug, $icon, $arTitle, $enTitle, $arEx, $enEx]) {
                $svc = Service::updateOrCreate(['slug' => $sslug], [
                    'category_id' => $cat->id,
                    'title' => $this->t($arTitle, $enTitle),
                    'excerpt' => $this->t($arEx, $enEx),
                    // TODO: confirm with client — service copy below is placeholder text
                    'body' => $this->t(
                        "<p>{$arEx}</p><p>نعتمد في هذه الخدمة على منهجيات معيارية وأدوات موثوقة، ونُشرك فريقك في كل مرحلة لضمان توافق المخرجات مع أولوياتك التشغيلية والتنظيمية.</p><h3>لمن تناسب هذه الخدمة؟</h3><p>البنوك وشركات الدفع والتمويل والمنصات المالية الناشئة التي تسعى إلى رفع مستوى الثقة والامتثال والجاهزية.</p>",
                        "<p>{$enEx}</p><p>We deliver this service using standard methodologies and trusted tooling, involving your team at every stage so the outputs match your operational and regulatory priorities.</p><h3>Who is this for?</h3><p>Banks, payment and financing companies, and emerging financial platforms aiming to raise trust, compliance and readiness.</p>",
                    ),
                    'deliverables' => $this->t(implode("\n", $tpl[2]), implode("\n", $tpl[3])),
                    'methodology' => $this->t(implode("\n", $tpl[0]), implode("\n", $tpl[1])),
                    'icon' => $icon, 'sort' => $i + 1, 'is_active' => true,
                    'seo' => null,
                ]);
                unset($svc);
            }
        }
    }

    private function posts(User $admin): void
    {
        $cats = [
            'cybersecurity' => $this->t('الأمن السيبراني', 'Cybersecurity'),
            'compliance' => $this->t('الامتثال', 'Compliance'),
            'fintech' => $this->t('التقنية المالية', 'Fintech'),
        ];
        foreach ($cats as $slug => $name) {
            $cats[$slug] = PostCategory::updateOrCreate(['slug' => $slug], ['name' => $name]);
        }

        // TODO: confirm with client — all post copy is placeholder content
        $posts = [
            ['pentest-payment-gateways', 'cybersecurity', 'لماذا يجب اختبار اختراق بوابات الدفع بانتظام؟', 'Why Payment Gateways Need Regular Penetration Testing',
                'الهجمات على بوابات الدفع تتطور باستمرار، والاختبار الدوري هو خط الدفاع الأول.', 'Attacks on payment gateways keep evolving, and regular testing is the first line of defence.', 3],
            ['pci-dss-4-readiness', 'compliance', 'خطوات عملية للاستعداد لمعيار PCI DSS 4.0', 'Practical Steps to Prepare for PCI DSS 4.0',
                'ما الذي تغيّر في الإصدار الرابع وكيف تبدأ رحلة الامتثال دون تعقيد.', 'What changed in version 4 and how to start your compliance journey without complexity.', 7],
            ['open-banking-saudi', 'fintech', 'الخدمات المصرفية المفتوحة في السعودية: الفرص والتحديات', 'Open Banking in Saudi Arabia: Opportunities and Challenges',
                'نظرة على كيفية تغيير الخدمات المصرفية المفتوحة لتجربة العملاء وما تتطلبه من أمان.', 'A look at how Open Banking reshapes customer experience and the security it demands.', 12],
            ['api-security-checklist', 'cybersecurity', 'قائمة تحقق لأمن واجهات الـ API المالية', 'A Security Checklist for Financial APIs',
                'عشر نقاط أساسية يجب مراجعتها قبل إتاحة أي واجهة برمجية لشركائك.', 'Ten essentials to review before exposing any API to your partners.', 18],
            ['pdpl-for-fintech', 'compliance', 'نظام حماية البيانات الشخصية وأثره على شركات التقنية المالية', 'The PDPL and What It Means for Fintech Companies',
                'التزامات الشركات تجاه بيانات العملاء وأفضل الممارسات للتطبيق.', 'Company obligations towards customer data and best practices for implementation.', 25],
            ['fraud-monitoring-basics', 'fintech', 'أساسيات رصد الاحتيال في المعاملات اللحظية', 'Fundamentals of Real-Time Transaction Fraud Monitoring',
                'كيف تجمع بين القواعد والتحليلات السلوكية لرصد الاحتيال دون إزعاج العملاء.', 'How to combine rules and behavioural analytics to catch fraud without annoying customers.', 31],
        ];

        foreach ($posts as $n => [$slug, $cat, $arT, $enT, $arE, $enE, $daysAgo]) {
            Post::updateOrCreate(['slug' => $slug], [
                'title' => $this->t($arT, $enT),
                'excerpt' => $this->t($arE, $enE),
                'body' => $this->t(
                    "<p>{$arE}</p><h2>الخلفية</h2><p>يشهد القطاع المالي تحولاً رقمياً متسارعاً، وكل خدمة جديدة تفتح سطح هجوم جديداً يتطلب ضبطاً أمنياً مدروساً منذ مرحلة التصميم.</p><h2>أبرز النقاط</h2><ul><li>ابدأ بتقييم المخاطر قبل اختيار الحلول.</li><li>اجعل الاختبار الأمني جزءاً من دورة التطوير.</li><li>وثّق الضوابط واحتفظ بأدلة التدقيق.</li></ul><h2>الخلاصة</h2><p>الأمن والامتثال ليسا عبئاً، بل ميزة تنافسية تبني ثقة العملاء والجهات التنظيمية.</p>",
                    "<p>{$enE}</p><h2>Background</h2><p>The financial sector is going through rapid digital change, and every new service opens a new attack surface that needs deliberate security design from day one.</p><h2>Key points</h2><ul><li>Start with a risk assessment before choosing solutions.</li><li>Make security testing part of the development lifecycle.</li><li>Document controls and keep audit evidence.</li></ul><h2>Conclusion</h2><p>Security and compliance are not a burden but a competitive advantage that builds trust with customers and regulators.</p>",
                ),
                'category_id' => $cats[$cat]->id,
                'author_id' => $admin->id,
                'published_at' => now()->subDays($daysAgo),
                'is_published' => true,
                'seo' => null,
            ]);
        }
    }

    /** Writes simple SVG wordmark placeholders and attaches them via the media library. */
    private function logos(): void
    {
        $dir = storage_path('app/seed-logos');
        File::ensureDirectoryExists($dir);

        $make = function (string $model, string $prefix, int $count) use ($dir) {
            foreach (range(1, $count) as $i) {
                $name = sprintf('%s %02d', $prefix, $i); // TODO: confirm with client — replace with real names/logos
                $record = $model::firstOrCreate(['name' => $name], ['sort' => $i, 'is_active' => true]);
                if ($record->hasMedia('logo')) {
                    continue;
                }
                $file = "$dir/".str_replace(' ', '-', strtolower($name)).'.svg';
                File::put($file, '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 60"><rect x="6" y="14" width="32" height="32" rx="8" fill="#B8913F"/><path d="M13 36l7-8 6 5 8-10" stroke="#0D1A4A" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/><text x="46" y="37" fill="#F5F1E6" font-family="Arial,sans-serif" font-size="18" font-weight="700">'.htmlspecialchars($name).'</text></svg>');
                $record->addMedia($file)->preservingOriginal()->toMediaCollection('logo');
            }
        };

        $make(Client::class, 'Client', 10);
        $make(Partner::class, 'Partner', 6);
    }

    private function stats(): void
    {
        // TODO: confirm with client — figures are placeholders
        $rows = [
            [$this->t('سنوات الخبرة', 'Years of experience'), 8, '+'],
            [$this->t('عميل', 'Clients'), 40, '+'],
            [$this->t('مشروع', 'Projects'), 120, '+'],
            [$this->t('شريك', 'Partners'), 15, '+'],
        ];
        foreach ($rows as $i => [$label, $value, $suffix]) {
            Stat::updateOrCreate(['sort' => $i + 1], ['label' => $label, 'value' => $value, 'suffix' => $suffix]);
        }
    }

    private function pages(): void
    {
        // TODO: confirm with client — legal texts need legal review before launch
        $pages = [
            ['about', $this->t('من نحن', 'About Us'), $this->t(
                '<p>سلسلة العلوم للتقنية المالية شركة سعودية متخصصة في حماية القطاع المالي وتطويره تقنياً. نقدم حلولاً متكاملة تبدأ من تقييم الأمن والامتثال وتمتد إلى بناء المنصات المالية الرقمية وتشغيلها.</p>',
                '<p>Silsilah Financial Technology Sciences is a Saudi company specialised in protecting and technically advancing the financial sector. We deliver integrated solutions from security and compliance assessment through to building and operating digital financial platforms.</p>')],
            ['privacy', $this->t('سياسة الخصوصية', 'Privacy Policy'), $this->t(
                '<h2>جمع البيانات</h2><p>نجمع البيانات التي تقدمها عبر نموذج التواصل (الاسم، البريد، الهاتف، الرسالة) بغرض الرد على استفسارك فقط.</p><h2>حماية البيانات</h2><p>نطبق ضوابط تقنية وتنظيمية مناسبة وفق نظام حماية البيانات الشخصية في المملكة.</p><h2>حقوقك</h2><p>يمكنك طلب الاطلاع على بياناتك أو تصحيحها أو حذفها عبر التواصل معنا.</p>',
                '<h2>Data collection</h2><p>We collect the data you submit through the contact form (name, email, phone, message) solely to respond to your enquiry.</p><h2>Data protection</h2><p>We apply appropriate technical and organisational controls in line with the Kingdom\'s Personal Data Protection Law.</p><h2>Your rights</h2><p>You may request access to, correction of, or deletion of your data by contacting us.</p>')],
            ['terms', $this->t('الشروط والأحكام', 'Terms & Conditions'), $this->t(
                '<h2>استخدام الموقع</h2><p>المحتوى المنشور للمعلومات العامة ولا يُعد استشارة ملزمة.</p><h2>الملكية الفكرية</h2><p>جميع المحتويات والعلامات محفوظة لسلسلة العلوم للتقنية المالية.</p><h2>القانون الواجب التطبيق</h2><p>تخضع هذه الشروط لأنظمة المملكة العربية السعودية.</p>',
                '<h2>Use of the site</h2><p>Published content is for general information and is not binding advice.</p><h2>Intellectual property</h2><p>All content and marks are reserved by Silsilah Financial Technology Sciences.</p><h2>Governing law</h2><p>These terms are governed by the laws of the Kingdom of Saudi Arabia.</p>')],
        ];
        foreach ($pages as [$slug, $title, $body]) {
            Page::updateOrCreate(['slug' => $slug], ['title' => $title, 'body' => $body]);
        }
    }

    private function settings(): void
    {
        // TODO: confirm with client — contact details, socials and analytics IDs are placeholders
        Setting::put('phone', '+966 11 000 0000');
        Setting::put('email', 'info@silsilah.example');
        Setting::put('address', $this->t('الرياض، المملكة العربية السعودية', 'Riyadh, Kingdom of Saudi Arabia'));
        Setting::put('socials', [
            ['platform' => 'X', 'url' => 'https://x.com/'],
            ['platform' => 'LinkedIn', 'url' => 'https://www.linkedin.com/'],
            ['platform' => 'Instagram', 'url' => 'https://www.instagram.com/'],
        ]);
        Setting::put('footer_text', $this->t(
            'سلسلة العلوم للتقنية المالية شركة سعودية متخصصة في حماية المنصات المالية وتطويرها.',
            'Silsilah Financial Technology Sciences is a Saudi company specialised in protecting and developing financial platforms.',
        ));
        Setting::put('ga_id', '');
        Setting::put('map_embed', '');
    }
}
