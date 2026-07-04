@extends('layouts.marketing')

@section('content')
    @php
        $marketingTranslations = [
            'app_name' => __('app.name'),
            'access_panel' => __('landing.access_panel'),
            'hero_title' => __('landing.hero_title'),
            'hero_description' => __('landing.hero_description'),
            'hero_kicker' => __('landing.hero_kicker'),
            'hero_control_total' => __('landing.hero_control_total'),
            'hero_preview_title' => __('landing.hero_preview_title'),
            'hero_preview_filter_title' => __('landing.hero_preview_filter_title'),
            'hero_preview_filter_desc' => __('landing.hero_preview_filter_desc'),
            'hero_preview_alert_title' => __('landing.hero_preview_alert_title'),
            'hero_preview_alert_desc' => __('landing.hero_preview_alert_desc'),
            'start_now' => __('landing.start_now'),
            'learn_more' => __('landing.learn_more'),
            'prize_draw_title' => __('landing.prize_draw_title'),
            'prize_amount_display' => __('landing.prize_amount_display'),
            'prize_draw_description' => __('landing.prize_draw_description'),
            'prize_draw_badge' => __('landing.prize_draw_badge'),
            'features_title' => __('landing.features_title'),
            'features_description' => __('landing.features_description'),
            'feature_redirect_title' => __('landing.feature_redirect_title'),
            'feature_redirect_desc' => __('landing.feature_redirect_desc'),
            'feature_protection_title' => __('landing.feature_protection_title'),
            'feature_protection_desc' => __('landing.feature_protection_desc'),
            'feature_dashboard_title' => __('landing.feature_dashboard_title'),
            'feature_dashboard_desc' => __('landing.feature_dashboard_desc'),
            'feature_notifications_title' => __('landing.feature_notifications_title'),
            'feature_notifications_desc' => __('landing.feature_notifications_desc'),
            'feature_contacts_title' => __('landing.feature_contacts_title'),
            'feature_contacts_desc' => __('landing.feature_contacts_desc'),
            'feature_multilang_title' => __('landing.feature_multilang_title'),
            'feature_multilang_desc' => __('landing.feature_multilang_desc'),
            'feature_export_title' => __('landing.feature_export_title'),
            'feature_export_desc' => __('landing.feature_export_desc'),
            'feature_customization_title' => __('landing.feature_customization_title'),
            'feature_customization_desc' => __('landing.feature_customization_desc'),
            'feature_darkmode_title' => __('landing.feature_darkmode_title'),
            'feature_darkmode_desc' => __('landing.feature_darkmode_desc'),
            'benefits_title' => __('landing.benefits_title'),
            'benefits_description' => __('landing.benefits_description'),
            'benefit1_title' => __('landing.benefit1_title'),
            'benefit1_desc' => __('landing.benefit1_desc'),
            'benefit2_title' => __('landing.benefit2_title'),
            'benefit2_desc' => __('landing.benefit2_desc'),
            'benefit3_title' => __('landing.benefit3_title'),
            'benefit3_desc' => __('landing.benefit3_desc'),
            'benefit4_title' => __('landing.benefit4_title'),
            'benefit4_desc' => __('landing.benefit4_desc'),
            'benefit5_title' => __('landing.benefit5_title'),
            'benefit5_desc' => __('landing.benefit5_desc'),
            'benefit6_title' => __('landing.benefit6_title'),
            'benefit6_desc' => __('landing.benefit6_desc'),
            'how_title' => __('landing.how_title'),
            'how_description' => __('landing.how_description'),
            'step1_title' => __('landing.step1_title'),
            'step1_desc' => __('landing.step1_desc'),
            'step2_title' => __('landing.step2_title'),
            'step2_desc' => __('landing.step2_desc'),
            'step3_title' => __('landing.step3_title'),
            'step3_desc' => __('landing.step3_desc'),
            'step4_title' => __('landing.step4_title'),
            'step4_desc' => __('landing.step4_desc'),
            'cta_title' => __('landing.cta_title'),
            'cta_description' => __('landing.cta_description'),
            'start_free' => __('landing.start_free'),
            'contact_form_title' => __('landing.contact_form_title'),
            'contact_form_subtitle' => __('landing.contact_form_subtitle'),
            'contact_name' => __('landing.contact_name'),
            'contact_name_placeholder' => __('landing.contact_name_placeholder'),
            'company_name' => __('landing.company_name'),
            'company_name_placeholder' => __('landing.company_name_placeholder'),
            'email' => __('landing.email'),
            'email_placeholder' => __('landing.email_placeholder'),
            'whatsapp' => __('landing.whatsapp'),
            'whatsapp_placeholder' => __('landing.whatsapp_placeholder'),
            'submit_button' => __('landing.submit_button'),
            'sending' => __('landing.sending'),
            'success_title' => __('landing.success_title'),
            'success_message' => __('landing.success_message'),
            'error_message' => __('landing.error_message'),
            'footer_description' => __('landing.footer_description'),
            'all_rights' => __('landing.all_rights'),
            'product' => __('landing.product'),
            'how_works' => __('landing.how_works'),
            'control_panel' => __('landing.control_panel'),
            'create_account' => __('landing.create_account'),
            'contact' => __('landing.contact'),
            'technical_support' => __('landing.technical_support'),
            'developed_with' => __('landing.developed_with'),
            'by' => __('landing.by'),
        ];

        $marketingStats = [
            ['label' => __('landing.reviews_processed'), 'value' => '+10k'],
            ['label' => __('landing.more_google_reviews'), 'value' => __('landing.hero_stat_growth_value')],
        ];

        $marketingAssets = [
            'logo' => asset('assets/images/lopgosDASHBOARD.png'),
        ];
    @endphp

    <div
        id="marketingRoot"
        data-translations='@json($marketingTranslations)'
        data-stats='@json($marketingStats)'
        data-assets='@json($marketingAssets)'
        data-csrf="{{ csrf_token() }}"
    ></div>
@endsection
