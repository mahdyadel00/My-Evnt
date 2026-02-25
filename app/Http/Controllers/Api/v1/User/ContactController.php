<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\User\Contactus\StoreContactRequest;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\SuccessResource;
use App\Models\Contact;
use App\Models\TermsCondittion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    protected function contactus(StoreContactRequest $request)
    {
        try {
            DB::beginTransaction();

            $contact = Contact::create($request->safe()->all());

            DB::commit();

            DB::commit();
            return new SuccessResource('Your message has been sent successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::channel('admin')->error('Error in ContactController@store: ' . $exception->getMessage() . ' at Line: ' . $exception->getLine() . ' in File: ' . $exception->getFile());
            return new ErrorResource('Something went wrong, please try again later', 500);
        }
    }


    public function termsAndConditions()
{
    $terms_conditions = TermsCondittion::first();

    if (!$terms_conditions) {
        return new ErrorResource(__('admin.not_found', ['attribute' => __('attributes.terms_conditions')]));
    }

    $cleaned_data = [
        'id' => $terms_conditions->id,
        'terms_condition' => $this->cleanText($terms_conditions->terms_condition),
        'privacy_policy' => $this->cleanText($terms_conditions->privacy_policy),
        'about_us' => $this->cleanText($terms_conditions->about_us),
        'shipping_payment' => $this->cleanText($terms_conditions->shipping_payment),
        'created_at' => $terms_conditions->created_at,
        'updated_at' => $terms_conditions->updated_at,
    ];

    return new SuccessResource([
        'message'   => $cleaned_data
    ]);
}

/**
 * دالة لتنظيف النصوص من المسافات الزيادة والتنسيقات الغير ضرورية
 * @param string $text النص اللي هيتم تنظيفه
 * @param bool $strip_tags لو عايز تزيل الـ HTML tags أو تسيبها
 * @return string النص بعد التنظيف
 */
private function cleanText($text, $strip_tags = false)
{
    if (empty($text)) {
        return $text;
    }

    // إزالة المسافات الزيادة والـ line breaks
    $cleaned_text = preg_replace('/\s+/', ' ', trim($text));

    // إزالة الـ \r\n واستبدالها بمسافة واحدة
    $cleaned_text = str_replace(["\r\n", "\r", "\n"], ' ', $cleaned_text);

    // إزالة الـ HTML Tags لو المطلوب
    if ($strip_tags) {
        $cleaned_text = strip_tags($cleaned_text);
    } else {
        // لو عايز تسيب الـ HTML، بس ننظف المسافات الزيادة داخل الـ Tags
        $cleaned_text = preg_replace('/>\s+</', '><', $cleaned_text);
    }

    return $cleaned_text;
}
}
