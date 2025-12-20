<?php
/**
 * Template for City Pages
 * Conversion-optimized for lead generation
 * FIBER-FOCUSED for maximum conversions
 */

get_header();

$city = get_post_meta(get_the_ID(), '_citysyncai_city', true);
$state = get_post_meta(get_the_ID(), '_citysyncai_state', true);
$city_full = "{$city}, {$state}";

// Get trust elements
$years_in_business = 25;
$clients_served = 2740;
$phone_number = '850-359-8004';
?>

<div class="citysyncai-city-page">
    
    <!-- HERO SECTION - Above the Fold -->
    <section class="city-hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">Business Fiber Internet in <?php echo esc_html($city_full); ?></h1>
                <p class="hero-subtitle">Check Fiber Availability & Compare All Carriers - Same Day Results, No Cost</p>
                
                <div class="hero-trust-signals">
                    <div class="trust-item">
                        <strong><?php echo esc_html($years_in_business); ?> Years</strong> in Business
                    </div>
                    <div class="trust-item">
                        <strong><?php echo esc_html(number_format($clients_served)); ?>+</strong> Clients Served
                    </div>
                    <div class="trust-item">
                        <strong>Same-Day</strong> Availability Info
                    </div>
                </div>
                
                <div class="hero-value-props">
                    <p>✓ Compare pricing from 50+ carriers</p>
                    <p>✓ No need to call multiple ISPs</p>
                    <p>✓ Save hours of research</p>
                    <p>✓ Free availability check - no obligation</p>
                </div>
            </div>
            
            <div class="hero-form">
                <div class="form-container">
                    <h2>Check Fiber Availability</h2>
                    <p class="form-subtitle">Get same-day availability information from all carriers</p>
                    
                    <?php
                    // Gravity Forms - Address Checker Form (ID will be configurable)
                    $address_form_id = get_option('citysyncai_address_form_id', 1);
                    echo do_shortcode('[gravityform id="' . intval($address_form_id) . '" title="false" description="false" ajax="true"]');
                    ?>
                    
                    <div class="form-trust">
                        <p><strong>Or call now:</strong></p>
                        <a href="tel:<?php echo esc_attr($phone_number); ?>" class="phone-link"><?php echo esc_html($phone_number); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- MAIN CONTENT SECTION -->
    <section class="city-content">
        <div class="content-container">
            <div class="main-content">
                
                <!-- Fiber-Focused Introduction -->
                <div class="content-section">
                    <h2>Business Fiber Internet in <?php echo esc_html($city_full); ?> - Complete Guide</h2>
                    <?php
                    // Main AI-generated content
                    the_content();
                    ?>
                </div>
                
                <!-- Services Grid -->
                <div class="content-section services-section">
                    <h2>Complete Business Telecom Services</h2>
                    <p>We provide comprehensive business telecom solutions for companies in <?php echo esc_html($city_full); ?>. From fiber internet to unified communications, we partner with 50+ carriers to find you the best options.</p>
                    
                    <div class="services-grid">
                        <div class="service-card">
                            <div class="service-icon">📡</div>
                            <h3>Business Fiber Internet</h3>
                            <p>High-speed fiber connectivity for businesses. Check availability at your address.</p>
                            <a href="#check-availability" class="service-link">Check Availability</a>
                        </div>
                        
                        <div class="service-card">
                            <div class="service-icon">☎️</div>
                            <h3>Business Phone Systems</h3>
                            <p>Enterprise phone systems with advanced features for modern businesses.</p>
                            <a href="#get-quote" class="service-link">Get Quote</a>
                        </div>
                        
                        <div class="service-card">
                            <div class="service-icon">🌐</div>
                            <h3>VoIP Solutions</h3>
                            <p>Cloud-based phone systems with flexibility and cost savings.</p>
                            <a href="#get-quote" class="service-link">Get Quote</a>
                        </div>
                        
                        <div class="service-card">
                            <div class="service-icon">☁️</div>
                            <h3>Cloud Services</h3>
                            <p>Scalable cloud solutions for data storage and applications.</p>
                            <a href="#get-quote" class="service-link">Get Quote</a>
                        </div>
                        
                        <div class="service-card">
                            <div class="service-icon">💬</div>
                            <h3>Unified Communications</h3>
                            <p>Integrated communication platforms connecting your team.</p>
                            <a href="#get-quote" class="service-link">Get Quote</a>
                        </div>
                        
                        <div class="service-card">
                            <div class="service-icon">🔒</div>
                            <h3>Network Security</h3>
                            <p>Enterprise-grade security solutions for your business network.</p>
                            <a href="#get-quote" class="service-link">Get Quote</a>
                        </div>
                    </div>
                    
                    <!-- Inline CTA Form -->
                    <div class="inline-cta">
                        <?php
                        $quote_form_id = get_option('citysyncai_quote_form_id', 2);
                        echo do_shortcode('[gravityform id="' . intval($quote_form_id) . '" title="false" description="false" ajax="true"]');
                        ?>
                    </div>
                </div>
                
                <!-- Why Choose Us Section -->
                <div class="content-section why-section">
                    <h2>Why Businesses in <?php echo esc_html($city); ?> Choose Us</h2>
                    <div class="why-grid">
                        <div class="why-item">
                            <h3>25 Years Experience</h3>
                            <p>We've been helping businesses find the best telecom solutions since <?php echo date('Y') - $years_in_business; ?>. Our expertise saves you time and money.</p>
                        </div>
                        <div class="why-item">
                            <h3><?php echo esc_html(number_format($clients_served)); ?>+ Clients Served</h3>
                            <p>Trusted by businesses across <?php echo esc_html($state); ?> and nationwide. We understand your unique telecom needs.</p>
                        </div>
                        <div class="why-item">
                            <h3>50+ Carrier Partners</h3>
                            <p>We work with all major carriers to compare options and find you the best pricing. No need to call multiple ISPs.</p>
                        </div>
                        <div class="why-item">
                            <h3>Same-Day Availability</h3>
                            <p>Get same-day availability information for your address. Free check, no obligation.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonials -->
                <div class="content-section testimonials-section">
                    <h2>What Our Clients Say</h2>
                    <div class="testimonials-grid">
                        <?php
                        // AI-generated testimonials (can be cached per city)
                        $testimonials = citysyncai_get_testimonials($city_full);
                        foreach ($testimonials as $testimonial) {
                            echo '<div class="testimonial-card">';
                            echo '<p class="testimonial-text">"' . esc_html($testimonial['text']) . '"</p>';
                            echo '<p class="testimonial-author">— ' . esc_html($testimonial['author']) . ', ' . esc_html($testimonial['company']) . '</p>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
                
                <!-- FAQ Section -->
                <div class="content-section faq-section">
                    <h2>Frequently Asked Questions - Business Fiber in <?php echo esc_html($city_full); ?></h2>
                    <?php
                    // AI-generated FAQ with schema
                    citysyncai_render_faq_section($city_full, get_the_ID());
                    ?>
                </div>
                
            </div>
            
            <!-- SIDEBAR -->
            <aside class="city-sidebar">
                <div class="sidebar-form sticky">
                    <h3>Get Free Quote</h3>
                    <?php
                    $sidebar_form_id = get_option('citysyncai_quote_form_id', 2);
                    echo do_shortcode('[gravityform id="' . intval($sidebar_form_id) . '" title="false" description="false" ajax="true"]');
                    ?>
                </div>
                
                <div class="sidebar-info">
                    <h3>Quick Facts</h3>
                    <ul>
                        <li><strong>City:</strong> <?php echo esc_html($city_full); ?></li>
                        <?php
                        $city_data = citysyncai_get_city_data($city, $state);
                        if ($city_data) {
                            echo '<li><strong>Population:</strong> ' . esc_html(number_format($city_data['population'] ?? 0)) . '</li>';
                        }
                        ?>
                        <li><strong>Service Area:</strong> <?php echo esc_html($city_full); ?> & Surrounding Areas</li>
                    </ul>
                </div>
                
                <div class="sidebar-contact">
                    <h3>Contact Us</h3>
                    <p><strong>Call Now:</strong></p>
                    <a href="tel:<?php echo esc_attr($phone_number); ?>" class="phone-link large"><?php echo esc_html($phone_number); ?></a>
                    <p><small>Click to call on mobile</small></p>
                </div>
                
                <div class="sidebar-trust">
                    <div class="trust-badge">
                        <strong><?php echo esc_html($years_in_business); ?> Years</strong>
                        <span>In Business</span>
                    </div>
                    <div class="trust-badge">
                        <strong><?php echo esc_html(number_format($clients_served)); ?>+</strong>
                        <span>Clients Served</span>
                    </div>
                </div>
            </aside>
        </div>
    </section>
    
    <!-- FINAL CTA SECTION -->
    <section class="final-cta">
        <div class="cta-container">
            <h2>Ready to Check Fiber Availability in <?php echo esc_html($city_full); ?>?</h2>
            <p>Get same-day availability information from all carriers. Free check, no obligation.</p>
            <?php
            $final_form_id = get_option('citysyncai_address_form_id', 1);
            echo do_shortcode('[gravityform id="' . intval($final_form_id) . '" title="false" description="false" ajax="true"]');
            ?>
            <p class="cta-phone">Or call <a href="tel:<?php echo esc_attr($phone_number); ?>"><?php echo esc_html($phone_number); ?></a> now</p>
        </div>
    </section>
    
</div>

<?php
get_footer();

