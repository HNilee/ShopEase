@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-4xl px-6 py-8">
    <div class="bg-white rounded-xl shadow-soft p-8">
        <h1 class="text-3xl font-bold mb-6">Terms and Conditions</h1>
        <div class="prose max-w-none" id="termsContent">
            <h2>1. Acceptance of Terms</h2>
            <p>By accessing and using ShopEase, you accept and agree to be bound by the terms and provision of this agreement.</p>

            <h2>2. Use License</h2>
            <p>Permission is granted to temporarily download one copy of the materials (information or software) on ShopEase's website for personal, non-commercial transitory viewing only.</p>

            <h2>3. Disclaimer</h2>
            <p>The materials on ShopEase's website are provided on an 'as is' basis. ShopEase makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>

            <h2>4. Limitations</h2>
            <p>In no event shall ShopEase or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on ShopEase's website.</p>

            <h2>5. Accuracy of Materials</h2>
            <p>The materials appearing on ShopEase's website could include technical, typographical, or photographic errors. ShopEase does not warrant that any of the materials on its website are accurate, complete or current.</p>

            <h2>6. Links</h2>
            <p>ShopEase has not reviewed all of the sites linked to its website and is not responsible for the contents of any such linked site.</p>

            <h2>7. Modifications</h2>
            <p>ShopEase may revise these terms of service for its website at any time without notice.</p>

            <h2>8. Governing Law</h2>
            <p>These terms and conditions are governed by and construed in accordance with the laws of Indonesia and you irrevocably submit to the exclusive jurisdiction of the courts in that State or location.</p>

            <h2>9. Seller Requirements</h2>
            <p>As a seller on ShopEase, you agree to:</p>
            <ul>
                <li>Provide accurate and truthful information about your products</li>
                <li>Maintain secure transaction practices</li>
                <li>Respond to customer inquiries promptly</li>
                <li>Deliver products as described</li>
                <li>Follow all applicable laws and regulations</li>
                <li>Not engage in any fraudulent activities</li>
            </ul>

            <h2>10. Buyer Protection</h2>
            <p>We are committed to providing a safe shopping environment. All transactions are monitored and we have measures in place to protect both buyers and sellers.</p>

            <h2>11. Prohibited Activities</h2>
            <p>The following activities are strictly prohibited:</p>
            <ul>
                <li>Scamming or fraudulent activities</li>
                <li>Selling counterfeit or illegal items</li>
                <li>Harassment or abuse towards other users</li>
                <li>Spamming or unsolicited advertising</li>
                <li>Attempting to bypass our security measures</li>
            </ul>

            <h2>12. Account Termination</h2>
            <p>We reserve the right to terminate or suspend accounts that violate our terms of service or engage in prohibited activities.</p>

            <h2>13. Privacy Policy</h2>
            <p>Your privacy is important to us. Please review our Privacy Policy to understand how we collect, use, and protect your information.</p>

            <h2>14. Changes to Terms</h2>
            <p>We may update these Terms and Conditions from time to time. We will notify you of any changes by posting the new Terms and Conditions on this page.</p>

            <h2>15. Contact Us</h2>
            <p>If you have any questions about these Terms and Conditions, please contact us at support@shopease.com</p>

            <div class="mt-8 p-4 bg-gray-100 rounded-lg">
                <p class="text-sm text-gray-600">Last updated: March 2026</p>
            </div>
        </div>

        <div class="mt-8 text-center">
            <button id="acceptTermsBtn" 
                    class="bg-primary text-white px-6 py-3 rounded-full hover:bg-primary-hover transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                I Accept the Terms and Conditions
            </button>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const termsContent = document.getElementById('termsContent');
    const acceptBtn = document.getElementById('acceptTermsBtn');
    
    let hasScrolledToBottom = false;
    
    function checkScroll() {
        const scrollHeight = termsContent.scrollHeight;
        const scrollTop = termsContent.scrollTop + termsContent.clientHeight;
        
        if (scrollTop >= scrollHeight - 10) {
            hasScrolledToBottom = true;
            acceptBtn.disabled = false;
        }
    }
    
    termsContent.addEventListener('scroll', checkScroll);
    
    // Also check on load
    setTimeout(checkScroll, 100);
    
    acceptBtn.addEventListener('click', function() {
        if (hasScrolledToBottom) {
            // Store acceptance in session and redirect back
            sessionStorage.setItem('terms_accepted', 'true');
            window.location.href = '{{ route("seller.application.form") }}';
        }
    });
});
</script>
@endsection