<?php 
$page_title = "Contact Us | IntelliRCM";
include 'includes/header.php'; 
?>

<!-- Hero Section -->
<section class="page-hero contact-hero">
    <div class="container">
        <h1 class="page-hero-title">Let's Talk</h1>
        <p class="page-hero-tagline">Ready to transform your revenue cycle? We're here to help.</p>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-main-section">
    <div class="container">
        <div class="contact-main-grid">
            <!-- Contact Info -->
            <div class="contact-info-side">
                <h2>Get in Touch</h2>
                <p class="contact-intro">Whether you're ready to partner with us or just exploring options, we'd love to hear from you. Our team typically responds within 24 hours.</p>

                <div class="contact-methods">
                    <div class="contact-method-item">
                        <div class="contact-method-icon">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <div class="contact-method-text">
                            <h4>Phone</h4>
                            <p><a href="tel:+13177081048">+1 (317) 708-1048</a></p>
                            <span class="contact-hours">Mon-Fri: 8am-6pm EST</span>
                        </div>
                    </div>

                    <div class="contact-method-item">
                        <div class="contact-method-icon">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                        <div class="contact-method-text">
                            <h4>Email</h4>
                            <p><a href="mailto:rcmsales@mangalaminfotech.com">rcmsales@mangalaminfotech.com</a></p>
                            <span class="contact-hours">24-hour response guarantee</span>
                        </div>
                    </div>

                    <div class="contact-method-item">
                        <div class="contact-method-icon">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect>
                                <polyline points="22,7 13,13 11,13 2,7"></polyline>
                            </svg>
                        </div>
                        <div class="contact-method-text">
                            <h4>Fax</h4>
                            <p>+1 (281) 606-0309</p>
                            <span class="contact-hours">For document submissions</span>
                        </div>
                    </div>
                </div>

                <!-- Office Locations -->
                <div class="office-locations-compact">
                    <h3>Our Offices</h3>
                    <div class="office-item-compact">
                        <strong>Houston, TX</strong>
                        <p>Mangalam Infotech USA<br>Houston, TX 77478</p>
                    </div>
                    <div class="office-item-compact">
                        <strong>New York, NY</strong>
                        <p>Mangalam Infotech USA<br>New York, NY 10010</p>
                    </div>
                    <div class="office-item-compact">
                        <strong>Ahmedabad, India</strong>
                        <p>Mangalam Information Technologies<br>Ahmedabad, GJ 380054</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-side">
                <div class="contact-form-container">
                    <h3>Send Us a Message</h3>
                    <p>Fill out the form below and our team will get back to you within 24 hours</p>

                    <div id="formMessage"></div>

                    <form id="contactForm" class="contact-form-main">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="action" value="contact">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name <span class="required">*</span></label>
                                <input type="text" id="firstName" name="firstName" required>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name <span class="required">*</span></label>
                                <input type="text" id="lastName" name="lastName" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number <span class="required">*</span></label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>

                        <div class="form-group">
                            <label for="practiceType">Practice Type <span class="required">*</span></label>
                            <select id="practiceType" name="practiceType" required>
                                <option value="">Select Practice Type</option>
                                <option value="solo">Solo Practitioner</option>
                                <option value="small">Small Practice (2-5 providers)</option>
                                <option value="medium">Medium Practice (6-15 providers)</option>
                                <option value="large">Large Group (15+ providers)</option>
                                <option value="hospital">Hospital/Health System</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="specialty">Medical Specialty <span class="required">*</span></label>
                            <select id="specialty" name="specialty" required>
                                <option value="">Select Your Specialty</option>
                                <option value="pediatrics">Pediatrics</option>
                                <option value="neurology">Neurology</option>
                                <option value="pulmonology">Pulmonology & Sleep Medicine</option>
                                <option value="cardiology">Cardiology</option>
                                <option value="orthopedics">Orthopedics</option>
                                <option value="primary-care">Primary Care</option>
                                <option value="gastro">Gastroenterology</option>
                                <option value="allergy">Allergy & Immunology</option>
                                <option value="other">Other Specialty</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="interest">What are you interested in? <span class="required">*</span></label>
                            <select id="interest" name="interest" required>
                                <option value="">Select Service</option>
                                <option value="full-rcm">Complete RCM Outsourcing</option>
                                <option value="coding">Medical Coding Only</option>
                                <option value="ar">A/R Follow-Up & Collections</option>
                                <option value="denials">Denial Management</option>
                                <option value="credentialing">Credentialing Services</option>
                                <option value="ai-scheduling">AI Scheduling Agent</option>
                                <option value="consultation">Free Revenue Analysis</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message">Tell Us About Your Practice</label>
                            <textarea id="message" name="message" rows="5" placeholder="Share your current challenges, goals, or questions..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-full">Send Message</button>

                        <p class="form-privacy">By submitting this form, you agree to our <a href="#">Privacy Policy</a>. We respect your privacy and will never share your information.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <div class="section-header-center">
            <h2 class="section-title">Frequently Asked Questions</h2>
        </div>

        <div class="faq-grid">
            <div class="faq-item">
                <h4>How quickly can we get started?</h4>
                <p>Most practices complete onboarding within 30-45 days. We'll work with your schedule to ensure zero disruption to your operations during the transition.</p>
            </div>

            <div class="faq-item">
                <h4>What EHR systems do you integrate with?</h4>
                <p>We integrate seamlessly with all major EHR/PM systems including Epic, Cerner, Athenahealth, eClinicalWorks, NextGen, Practice Fusion, and many others.</p>
            </div>

            <div class="faq-item">
                <h4>How is pricing structured?</h4>
                <p>We offer transparent, performance-based pricing as a percentage of collections. No hidden fees, no long-term contracts. You pay for results, not promises.</p>
            </div>

            <div class="faq-item">
                <h4>Will I have a dedicated account manager?</h4>
                <p>Yes! Every client is assigned a dedicated account manager and specialist team who learn your practice inside and out. You'll have direct phone, email, and portal access.</p>
            </div>

            <div class="faq-item">
                <h4>What happens to my current billing staff?</h4>
                <p>That's entirely up to you. Many practices redeploy staff to patient care or administrative roles. We can also provide transition support and training if desired.</p>
            </div>

            <div class="faq-item">
                <h4>Do you handle credentialing and re-credentialing?</h4>
                <p>Absolutely. We manage all payer enrollment, contract negotiation, and ongoing re-credentialing to keep you in-network without interruption.</p>
            </div>
        </div>

        <div class="faq-cta">
            <p>Don't see your question answered?</p>
            <a href="#contactForm" class="btn btn-secondary">Ask Us Directly</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
