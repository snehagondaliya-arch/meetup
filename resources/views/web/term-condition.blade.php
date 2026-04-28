@extends('layouts.master')
@section('title', 'MeetUp | Index')
@section('no-sidebar', true)
@section('content')
<!-- Start Contact-Us Section -->
<section class="disclaimer-section section-s1padding">
    <div class="container max-w-1000px">
        <div class="row">
            <div class="col-12">
                <div class="section-title-s1 mb-md-5 mb-4">
                    <div>
                        <span class="badge-s1">Events</span>
                    </div>
                    <h2>Term And Conditions</h2>
                    <p>Everything you need to know about events and communities</p>
                </div>
            </div>
            <div class="col-12">
                <div class="breadcrumb-section mb-md-5 mb-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-arrow d-flex justify-content-center mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Term And Conditions</li>
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
                        <p>Welcome to <b>eventtime.com</b>. By accessing or using this website, you agree to comply with
                            and be bound by the following Terms & Conditions. If you do not agree with any part of these
                            terms, please do not use our platform.</p>

                        <h3 class="fs-22px gt-text-theme">Use of the Website</h3>
                        <p>eventtime.com provides a platform to discover events, connect with communities, and explore
                            shared interests. You agree to use the website only for lawful purposes and in a way that
                            does not harm, restrict, or interfere with other users.</p>

                        <ul class="ps-4">
                            <li class="pb-1">Do not misuse the platform or attempt unauthorized access</li>
                            <li class="pb-1">Do not upload harmful, illegal, or misleading content</li>
                            <li class="pb-1">Respect other users and community guidelines</li>
                        </ul>

                        <h3 class="fs-22px gt-text-theme">User Responsibilities</h3>
                        <p>By using eventtime.com, you agree that:</p>

                        <ul class="ps-4">
                            <li class="pb-1">All information you provide is accurate and up to date</li>
                            <li class="pb-1">You are responsible for your activity on the platform</li>
                            <li class="pb-1">You will not engage in abusive, harmful, or illegal behavior</li>
                        </ul>

                        <h3 class="fs-22px gt-text-theme">Event Information</h3>
                        <p>We aim to provide accurate and updated event information. However, eventtime.com does not
                            guarantee the completeness or reliability of event details such as time, location, or
                            availability.</p>

                        <p>Event organizers are responsible for the content they publish. Users should verify details
                            before attending any event.</p>

                        <h3 class="fs-22px gt-text-theme">Third-Party Links</h3>
                        <p>Our platform may include links to third-party websites or services. We do not control or take
                            responsibility for their content, policies, or practices.</p>

                        <p>Accessing third-party websites is at your own risk.</p>

                        <h3 class="fs-22px gt-text-theme">Intellectual Property</h3>
                        <p>All content on eventtime.com, including text, design, logos, and graphics, is the property of
                            eventtime.com unless otherwise stated. You may not copy, reproduce, or distribute content
                            without permission.</p>

                        <h3 class="fs-22px gt-text-theme">Limitation of Liability</h3>
                        <p>eventtime.com is provided “as is” without warranties of any kind. We are not responsible for:
                        </p>

                        <ul class="ps-4">
                            <li class="pb-1">Event cancellations or changes</li>
                            <li class="pb-1">User interactions or disputes</li>
                            <li class="pb-1">Losses or damages from using the platform</li>
                            <li class="pb-1">Technical issues or downtime</li>
                        </ul>

                        <h3 class="fs-22px gt-text-theme">Changes to Terms</h3>
                        <p>We may update these Terms & Conditions at any time without prior notice. Changes will be
                            posted on this page with an updated “Last updated” date.</p>

                        <p>Your continued use of the website means you accept the revised terms.</p>

                        <h3 class="fs-22px gt-text-theme">Termination</h3>
                        <p>We reserve the right to suspend or terminate access to the platform at any time if users
                            violate these terms or engage in harmful behavior.</p>

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

