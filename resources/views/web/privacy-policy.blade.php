@extends('layouts.master')
@section('title',config('app.name'))

@section('content')
    <!-- Start Contact-Us Section -->
    <section class="disclaimer-section section-s1padding">
        <div class="container max-w-1000px">
            <div class="row">
                <div class="col-12">
                    <div class="section-title-s1 mb-3">
                        <div>
                            <span class="badge-s1">Events</span>
                        </div>
                        <h2>Privay Policy</h2>
                        <p>Everything you need to know about events and communities</p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="breadcrumb-section mb-md-5 mb-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-arrow d-flex justify-content-center mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Privacy Policy</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row row-gap-3">
                <div class="col-12">
                    <div class="card card-s2">
                        <div class="card-body p-md-4 p-3">
                            <p><b>Last updated:</b> April 10, 2026</p>

                            <p>Welcome to <b>eventtime.com</b>. Your privacy is important to us. This Privacy Policy explains how we collect, use, and protect your information when you use our website.</p>

                            <h3 class="fs-22px gt-text-theme">Information We Collect</h3>
                            <p>We may collect the following types of information:</p>

                            <ul class="ps-4">
                                <li class="pb-1"><b>Personal Information:</b> such as your name, email address, or contact details when you interact with us</li>
                                <li class="pb-1"><b>Usage Data:</b> information about how you use our website, including pages visited and actions taken</li>
                                <li class="pb-1"><b>Device Information:</b> browser type, device type, and IP address for analytics and performance</li>
                            </ul>

                            <h3 class="fs-22px gt-text-theme">How We Use Your Information</h3>
                            <p>We use the information we collect to:</p>

                            <ul class="ps-4">
                                <li class="pb-1">Provide and improve our platform and services</li>
                                <li class="pb-1">Help you discover relevant events and communities</li>
                                <li class="pb-1">Respond to your inquiries or support requests</li>
                                <li class="pb-1">Analyze usage trends and improve user experience</li>
                            </ul>

                            <h3 class="fs-22px gt-text-theme">Cookies and Tracking Technologies</h3>
                            <p>We use cookies and similar technologies to enhance your browsing experience. Cookies help us understand user behavior and improve website functionality.</p>

                            <p>You can control or disable cookies through your browser settings.</p>

                            <h3 class="fs-22px gt-text-theme">Sharing of Information</h3>
                            <p>We do not sell or rent your personal information. We may share data only in the following cases:</p>

                            <ul class="ps-4">
                                <li class="pb-1">With trusted service providers who help operate our platform</li>
                                <li class="pb-1">When required by law or legal processes</li>
                                <li class="pb-1">To protect our rights, users, or platform safety</li>
                            </ul>

                            <h3 class="fs-22px gt-text-theme">Data Security</h3>
                            <p>We take reasonable steps to protect your information from unauthorized access, loss, or misuse. However, no system is completely secure, and we cannot guarantee absolute security.</p>

                            <h3 class="fs-22px gt-text-theme">Third-Party Links</h3>
                            <p>Our website may contain links to external websites. We are not responsible for the privacy practices or content of third-party sites.</p>

                            <p>We encourage you to review their privacy policies before sharing any information.</p>

                            <h3 class="fs-22px gt-text-theme">Your Rights</h3>
                            <p>You have the right to:</p>

                            <ul class="ps-4">
                                <li class="pb-1">Access or update your personal information</li>
                                <li class="pb-1">Request deletion of your data (where applicable)</li>
                                <li class="pb-1">Opt out of certain data collection practices</li>
                            </ul>

                            <h3 class="fs-22px gt-text-theme">Changes to This Policy</h3>
                            <p>We may update this Privacy Policy from time to time. Any changes will be posted on this page with an updated “Last updated” date.</p>

                            <p>Your continued use of the website means you accept the updated policy.</p>

                            <h3 class="fs-22px gt-text-theme">Contact Us</h3>

                            <a href="#" class="fw-600 lh-normal fs-18px d-inline-flex mb-3">
                                <i class="fa-solid fa-envelope me-2 my-auto fs-22px gt-text-theme"></i>
                                support@eventtime.com
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Contact-Us Section -->
@endsection
