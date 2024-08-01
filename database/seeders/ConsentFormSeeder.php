<?php

namespace Database\Seeders;

use App\Models\ConsentForm\ConsentForm;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsentFormSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConsentForm::truncate();

        // For Doctor
        ConsentForm::create([
            'consent_form_type_id' => '1',
            'publish_status' => 'PENDING',
            'version' => '1.0.1',
            'content' => 'Terms and conditions are aimed at protecting the business (you). They give business owners the opportunity to set their rules (within applicable law) of how their service or product may be used including, but not limited to, things like copyright conditions, age limits, and the governing law of the contract. While terms are generally not legally required (like the privacy policy), it is essential for protecting your interests as a business owner.',
            'content_arabic' => 'إذا كنت تقوم بتثبيت مكونات نظام التشغيل نيابة عن شركة بخلاف شركتك،فإنه يتعين عليك التأكد أن المستخدم النهائي (سواءً كان شخصية طبيعية أم اعتبارية) قد تسلم هذه البنود والشروط وقام بقراءتها وإقرارها قبل تثبيت أيٍ من مكونات نظام التشغيل',
            'content_status' => 'DRAFT',
            'created_by' => 'practicenew1133',
            'practice_id' => 1,
        ], 200);
        ConsentForm::create([
            'consent_form_type_id' => '1',
            'publish_status' => 'ACTIVE',
            'version' => '1.0.2',
            'content' => 'Terms and conditions are aimed at protecting the business (you). They give business owners the opportunity to set their rules (within applicable law) of how their service or product may be used including, but not limited to, things like copyright conditions, age limits, and the governing law of the contract. While terms are generally not legally required (like the privacy policy), it is essential for protecting your interests as a business owner.',
            'content_arabic' => 'إذا كنت تقوم بتثبيت مكونات نظام التشغيل نيابة عن شركة بخلاف شركتك،فإنه يتعين عليك التأكد أن المستخدم النهائي (سواءً كان شخصية طبيعية أم اعتبارية) قد تسلم هذه البنود والشروط وقام بقراءتها وإقرارها قبل تثبيت أيٍ من مكونات نظام التشغيل',
            'content_status' => 'SAVE',
            'created_by' => 'practicenew1133',
            'practice_id' => 1,
        ], 200);
        ConsentForm::create([
            'consent_form_type_id' => '2',
            'publish_status' => 'ACTIVE',
            'version' => '1.0.1',
            'content' => 'Terms and conditions are aimed at protecting the business (you). They give business owners the opportunity to set their rules (within applicable law) of how their service or product may be used including, but not limited to, things like copyright conditions, age limits, and the governing law of the contract. While terms are generally not legally required (like the privacy policy), it is essential for protecting your interests as a business owner.',
            'content_arabic' => 'إذا كنت تقوم بتثبيت مكونات نظام التشغيل نيابة عن شركة بخلاف شركتك،فإنه يتعين عليك التأكد أن المستخدم النهائي (سواءً كان شخصية طبيعية أم اعتبارية) قد تسلم هذه البنود والشروط وقام بقراءتها وإقرارها قبل تثبيت أيٍ من مكونات نظام التشغيل',
            'content_status' => 'SAVE',
            'created_by' => 'practicenew1133',
            'published_at' => Carbon::now(),
            'practice_id' => 1,
        ], 200);
        ConsentForm::create([
            'consent_form_type_id' => '2',
            'publish_status' => 'PENDING',
            'version' => '1.0.2',
            'content' => 'Terms and conditions are aimed at protecting the business (you). They give business owners the opportunity to set their rules (within applicable law) of how their service or product may be used including, but not limited to, things like copyright conditions, age limits, and the governing law of the contract. While terms are generally not legally required (like the privacy policy), it is essential for protecting your interests as a business owner.',
            'content_arabic' => 'إذا كنت تقوم بتثبيت مكونات نظام التشغيل نيابة عن شركة بخلاف شركتك،فإنه يتعين عليك التأكد أن المستخدم النهائي (سواءً كان شخصية طبيعية أم اعتبارية) قد تسلم هذه البنود والشروط وقام بقراءتها وإقرارها قبل تثبيت أيٍ من مكونات نظام التشغيل',
            'content_status' => 'DRAFT',
            'created_by' => 'practicenew1133',
            'practice_id' => 1,
        ], 200);
        ConsentForm::create([
            'consent_form_type_id' => '2',
            'publish_status' => 'DEACTIVATE',
            'version' => '1.0.2',
            'content' => 'Terms and conditions are aimed at protecting the business (you). They give business owners the opportunity to set their rules (within applicable law) of how their service or product may be used including, but not limited to, things like copyright conditions, age limits, and the governing law of the contract. While terms are generally not legally required (like the privacy policy), it is essential for protecting your interests as a business owner.',
            'content_arabic' => 'إذا كنت تقوم بتثبيت مكونات نظام التشغيل نيابة عن شركة بخلاف شركتك،فإنه يتعين عليك التأكد أن المستخدم النهائي (سواءً كان شخصية طبيعية أم اعتبارية) قد تسلم هذه البنود والشروط وقام بقراءتها وإقرارها قبل تثبيت أيٍ من مكونات نظام التشغيل',
            'content_status' => 'SAVE',
            'created_by' => 'practicenew1133',
            'deactivated_at' => Carbon::now(),
            'practice_id' => 1,
        ], 200);

        // For Patient
        ConsentForm::create([
            'consent_form_type_id' => '4',
            'publish_status' => 'PENDING',
            'version' => '1.0.1',
            'content' => 'Terms and conditions are aimed at protecting the business (you). They give business owners the opportunity to set their rules (within applicable law) of how their service or product may be used including, but not limited to, things like copyright conditions, age limits, and the governing law of the contract. While terms are generally not legally required (like the privacy policy), it is essential for protecting your interests as a business owner.',
            'content_arabic' => 'إذا كنت تقوم بتثبيت مكونات نظام التشغيل نيابة عن شركة بخلاف شركتك،فإنه يتعين عليك التأكد أن المستخدم النهائي (سواءً كان شخصية طبيعية أم اعتبارية) قد تسلم هذه البنود والشروط وقام بقراءتها وإقرارها قبل تثبيت أيٍ من مكونات نظام التشغيل',
            'content_status' => 'DRAFT',
            'created_by' => 'practicenew1133',
            'practice_id' => 1,
        ], 200);
        ConsentForm::create([
            'consent_form_type_id' => '4',
            'publish_status' => 'ACTIVE',
            'version' => '1.0.2',
            'content' => 'Terms and conditions are aimed at protecting the business (you). They give business owners the opportunity to set their rules (within applicable law) of how their service or product may be used including, but not limited to, things like copyright conditions, age limits, and the governing law of the contract. While terms are generally not legally required (like the privacy policy), it is essential for protecting your interests as a business owner.',
            'content_arabic' => 'إذا كنت تقوم بتثبيت مكونات نظام التشغيل نيابة عن شركة بخلاف شركتك،فإنه يتعين عليك التأكد أن المستخدم النهائي (سواءً كان شخصية طبيعية أم اعتبارية) قد تسلم هذه البنود والشروط وقام بقراءتها وإقرارها قبل تثبيت أيٍ من مكونات نظام التشغيل',
            'content_status' => 'SAVE',
            'created_by' => 'practicenew1133',
            'practice_id' => 1,
        ], 200);
        ConsentForm::create([
            'consent_form_type_id' => '5',
            'publish_status' => 'ACTIVE',
            'version' => '1.0.1',
            'content' => 'Terms and conditions are aimed at protecting the business (you). They give business owners the opportunity to set their rules (within applicable law) of how their service or product may be used including, but not limited to, things like copyright conditions, age limits, and the governing law of the contract. While terms are generally not legally required (like the privacy policy), it is essential for protecting your interests as a business owner.',
            'content_arabic' => 'إذا كنت تقوم بتثبيت مكونات نظام التشغيل نيابة عن شركة بخلاف شركتك،فإنه يتعين عليك التأكد أن المستخدم النهائي (سواءً كان شخصية طبيعية أم اعتبارية) قد تسلم هذه البنود والشروط وقام بقراءتها وإقرارها قبل تثبيت أيٍ من مكونات نظام التشغيل',
            'content_status' => 'SAVE',
            'created_by' => 'practicenew1133',
            'published_at' => Carbon::now(),
            'practice_id' => 1,
        ], 200);
        ConsentForm::create([
            'consent_form_type_id' => '5',
            'publish_status' => 'PENDING',
            'version' => '1.0.2',
            'content' => 'Terms and conditions are aimed at protecting the business (you). They give business owners the opportunity to set their rules (within applicable law) of how their service or product may be used including, but not limited to, things like copyright conditions, age limits, and the governing law of the contract. While terms are generally not legally required (like the privacy policy), it is essential for protecting your interests as a business owner.',
            'content_arabic' => 'إذا كنت تقوم بتثبيت مكونات نظام التشغيل نيابة عن شركة بخلاف شركتك،فإنه يتعين عليك التأكد أن المستخدم النهائي (سواءً كان شخصية طبيعية أم اعتبارية) قد تسلم هذه البنود والشروط وقام بقراءتها وإقرارها قبل تثبيت أيٍ من مكونات نظام التشغيل',
            'content_status' => 'DRAFT',
            'created_by' => 'practicenew1133',
            'practice_id' => 1,
        ], 200);
        ConsentForm::create([
            'consent_form_type_id' => '5',
            'publish_status' => 'DEACTIVATE',
            'version' => '1.0.2',
            'content' => 'Terms and conditions are aimed at protecting the business (you). They give business owners the opportunity to set their rules (within applicable law) of how their service or product may be used including, but not limited to, things like copyright conditions, age limits, and the governing law of the contract. While terms are generally not legally required (like the privacy policy), it is essential for protecting your interests as a business owner.',
            'content_arabic' => 'إذا كنت تقوم بتثبيت مكونات نظام التشغيل نيابة عن شركة بخلاف شركتك،فإنه يتعين عليك التأكد أن المستخدم النهائي (سواءً كان شخصية طبيعية أم اعتبارية) قد تسلم هذه البنود والشروط وقام بقراءتها وإقرارها قبل تثبيت أيٍ من مكونات نظام التشغيل',
            'content_status' => 'SAVE',
            'created_by' => 'practicenew1133',
            'deactivated_at' => Carbon::now(),
            'practice_id' => 1,
        ], 200);
        ConsentForm::create([
            'consent_form_type_id' => '7',
            'publish_status' => 'ACTIVE',
            'version' => '1.0.2',
            'content' => 'Terms and conditions are aimed at protecting the business (you). They give business owners the opportunity to set their rules (within applicable law) of how their service or product may be used including, but not limited to, things like copyright conditions, age limits, and the governing law of the contract. While terms are generally not legally required (like the privacy policy), it is essential for protecting your interests as a business owner.',
            'content_arabic' => 'إذا كنت تقوم بتثبيت مكونات نظام التشغيل نيابة عن شركة بخلاف شركتك،فإنه يتعين عليك التأكد أن المستخدم النهائي (سواءً كان شخصية طبيعية أم اعتبارية) قد تسلم هذه البنود والشروط وقام بقراءتها وإقرارها قبل تثبيت أيٍ من مكونات نظام التشغيل',
            'content_status' => 'SAVE',
            'created_by' => 'practicenew1133',
            'deactivated_at' => Carbon::now(),
            'practice_id' => 1,
        ], 200);
    }
}
