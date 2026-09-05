    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- bootstrap css link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/animation.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/responsive.min.css') }}">

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&amp;display=swap" />

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&amp;family=Poppins:wght@300;400;500;600;700;800&amp;display=swap"
        rel="stylesheet">

    <link rel='stylesheet' href='https://cdn.rawgit.com/michalsnik/aos/2.0.4/dist/aos.css'>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- AOS Animation link -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
        .gateway-branding {
            width: 70%;
            margin: 10px;
        }
        /*.my_navbar{*/
        /*    background: {{about_us()->menubar_color}} !important;*/
        /*}*/
        /*.navbar_right .nav_details a{*/
        /*    color: {{about_us()->menubar_text_color}} !important;*/
        /*}*/
        /*.navbar_right .nav_details a:hover {*/
        /*    background: {{about_us()->menubar_color}} !important;*/
        /*}*/
        /*.toggle_menu{*/
        /*    background: {{about_us()->menubar_overlay_color}} !important;*/
        /*    color: {{about_us()->menubar_overlay_text_color}} !important;*/
        /*}*/
        /*.nav_modal .modal-dialog .modal-content{*/
        /*    background: {{about_us()->menubar_overlay_color}} !important;*/
        /*}*/
        /*.menus a{*/
        /*    color: {{about_us()->menubar_overlay_text_color}} !important;*/
        /*}*/
        /*section.footer {*/
        /*    background: {{about_us()->footer_bg}} !important;*/
        /*}*/
        /*section.copyright {*/
        /*    background: {{about_us()->footer_bg}} !important;*/
        /*}*/
        
        /*.inquire_form .send_btn button {*/
        /*    background: {{about_us()->button_color}} !important;*/
        /*    color: {{about_us()->button_text_color}} !important;*/
        /*}*/
        /*.inquire_form .send_btn button:hover {*/
        /*    background: {{about_us()->button_hover_color}} !important;*/
        /*    color: {{about_us()->button_hover_text_color}} !important;*/
        /*}*/
        
        /*.connect_us_btn a {*/
        /*    background: {{about_us()->button_color}} !important;*/
        /*    color: {{about_us()->button_text_color}} !important;*/
        /*}*/
        
        /*.connect_us_btn a:hover {*/
        /*    background: {{about_us()->button_hover_color}} !important;*/
        /*    color: {{about_us()->button_hover_text_color}} !important;*/
        /*}*/
        
        /*.connect_us_info h2 {*/
        /*    color: {{about_us()->header_text_color}} !important;*/
        /*}*/
        /*.title {*/
        /*    color: {{about_us()->header_text_color}} !important;*/
        /*}*/
        /*.service-content p{*/
        /*    color: {{about_us()->service_text_color}} !important;*/
        /*}*/
        /*.service-content p span{*/
        /*    color: {{about_us()->service_text_color}} !important;*/
        /*}*/
        /*.service_card .card_box:hover {*/
        /*    background: {{about_us()->service_hover_bg}} !important;*/
        /*    color: {{about_us()->service_hover_text_color}} !important;*/
        /*}*/
        /*.service_card .card_box:hover .service-content p{*/
        /*    color: {{about_us()->service_hover_text_color}} !important;*/
        /*}*/
        /*.service_card .card_box:hover .service-content p span{*/
        /*    color: {{about_us()->service_hover_text_color}} !important;*/
        /*}*/
        /*.service_card .card_box.active {*/
        /*    background: {{about_us()->service_hover_bg}} !important;*/
        /*    color: {{about_us()->service_hover_text_color}} !important;*/
        /*}*/
        /*.service_card .icon{*/
        /*    background: #ffffff;*/
            /*background: {{about_us()->service_hover_bg}};*/
        /*}*/
        /*.service_card .card_box:hover .service_card .icon{*/
        /*    background: #ffffff;*/
            /*background: {{about_us()->service_bg}};*/
        /*}*/
        /*.connect_with_us_inner{*/
        /*    background: {{about_us()->refer_area_bg}} !important;*/
        /*}*/
        /*.connect_us_info_text p{*/
        /*    color: {{about_us()->refer_area_text_color}} !important;*/
        /*}*/
        
        /*.global_subtitle {*/
        /*    color: {{about_us()->login_register_title_color}} !important;*/
        /*}*/
        /*.contact_inner_section {*/
        /*    background: {{about_us()->login_register_content_bg}} !important;*/
        /*}*/
        /*.contact_inner_section .details_inner .details_info p {*/
        /*    color: {{about_us()->login_register_content_color}} !important;*/
        /*}*/
        /*.contact_inner_section .details_inner .details_info h1,*/
        /*.contact_inner_section .details_inner .details_info h2,*/
        /*.contact_inner_section .details_inner .details_info h3,*/
        /*.contact_inner_section .details_inner .details_info h4,*/
        /*.contact_inner_section .details_inner .details_info h5,*/
        /*.contact_inner_section .details_inner .details_info h6*/
        /*{*/
        /*    color: {{about_us()->login_register_content_color}} !important;*/
        /*}*/
        /*.contact_inner_section .inquire_form .inquire_header {*/
        /*    background: {{about_us()->login_register_form_title_bg}} !important;*/
        /*}*/
        /*.contact_inner_section .inquire_form .inquire_header h2 {*/
        /*    color: {{about_us()->login_register_form_title_color}} !important;*/
        /*}*/
        /*.contact_inner_section .inquire_form {*/
        /*    background: {{about_us()->login_register_form_bg}} !important;*/
        /*}*/
    </style>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    