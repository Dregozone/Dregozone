<div>
    <!-- Header -->
    <section class="bg-stone-50 border-b border-stone-100">
        <div class="max-w-4xl mx-auto px-6 py-16">
            <p class="text-xs font-bold uppercase tracking-widest text-amber-600 mb-3">Legal</p>
            <h1 class="text-4xl md:text-5xl font-black text-stone-900 tracking-tight mb-4">Privacy Policy</h1>
            <p class="text-stone-500 text-sm">Last updated: {{ date('F j, Y') }}</p>
        </div>
    </section>

    <!-- Content -->
    <div class="bg-white">
        <div class="max-w-4xl mx-auto px-6 py-14 prose prose-stone prose-lg max-w-none">

            <p class="lead text-stone-600">
                This Privacy Policy explains how Anders Learmonth (<strong>"I", "me", or "my"</strong>) collects,
                uses, and protects information that you provide when using this website
                (<strong>dregozone.com</strong>). I am committed to ensuring that your privacy is protected and
                that this website complies with the UK General Data Protection Regulation (UK GDPR) and the Data
                Protection Act 2018.
            </p>

            <hr class="border-stone-200 my-10">

            <h2>1. Who is the Data Controller?</h2>
            <p>
                The data controller responsible for your personal information on this website is:
            </p>
            <address class="not-italic bg-stone-50 border border-stone-200 rounded-xl p-6 text-stone-700 text-base">
                <strong>Anders Learmonth</strong><br>
                Founder, Glacial Studio<br>
                Email: <a href="{{ route('contact') }}" class="text-amber-600 hover:text-amber-700">Contact via website</a>
            </address>

            <hr class="border-stone-200 my-10">

            <h2>2. What Personal Data I Collect</h2>
            <p>I collect the following categories of personal data:</p>

            <h3>a) Newsletter Subscriptions</h3>
            <ul>
                <li><strong>Email address</strong> — used to send you occasional blog updates and newsletters.</li>
                <li><strong>Name</strong> (optional) — used to personalise newsletter emails.</li>
                <li><strong>Subscription date</strong> — recorded for administrative purposes.</li>
            </ul>
            <p>
                Your email address is collected with your explicit consent when you complete the newsletter sign-up
                form. You can withdraw consent and unsubscribe at any time (see Section 6).
            </p>

            <h3>b) Contact Form Submissions</h3>
            <p>When you submit a message via the contact form, I collect:</p>
            <ul>
                <li><strong>Name</strong></li>
                <li><strong>Email address</strong></li>
                <li><strong>Subject and message content</strong></li>
                <li><strong>Enquiry type</strong> (e.g. general, work request, partnership)</li>
                <li><strong>Additional project details</strong> where provided (e.g. budget, timeline, company name)</li>
            </ul>
            <p>
                This information is processed to respond to your enquiry. It is stored securely on the website server
                and accessible only to me.
            </p>

            <h3>c) Technical / Usage Data</h3>
            <ul>
                <li><strong>Blog post view counts</strong> — individual page views are counted anonymously to understand which content is most popular. No personally identifiable information is stored alongside view counts.</li>
            </ul>
            <p>I do <strong>not</strong> currently use third-party analytics, advertising networks, or tracking pixels.</p>

            <hr class="border-stone-200 my-10">

            <h2>3. Legal Basis for Processing</h2>
            <table>
                <thead>
                    <tr>
                        <th>Purpose</th>
                        <th>Legal Basis (UK GDPR)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Sending newsletter emails</td>
                        <td>Consent (Art. 6(1)(a))</td>
                    </tr>
                    <tr>
                        <td>Responding to contact enquiries</td>
                        <td>Legitimate interests (Art. 6(1)(f))</td>
                    </tr>
                    <tr>
                        <td>Anonymous page view counting</td>
                        <td>Legitimate interests (Art. 6(1)(f))</td>
                    </tr>
                </tbody>
            </table>

            <hr class="border-stone-200 my-10">

            <h2>4. How Long I Keep Your Data</h2>
            <ul>
                <li><strong>Newsletter subscribers</strong> — your email address is retained while your subscription is active. If you unsubscribe, your record is marked inactive and will be deleted within 30 days of a deletion request.</li>
                <li><strong>Contact messages</strong> — kept for up to 2 years from receipt, or until the enquiry is fully resolved and no longer required.</li>
            </ul>

            <hr class="border-stone-200 my-10">

            <h2>5. Cookies</h2>
            <p>
                This website uses a small number of cookies that are essential for it to function correctly. These
                include a session cookie used by the Laravel framework for security (CSRF protection) and to maintain
                your login state if you have an account.
            </p>
            <p>
                When you visit the site for the first time, a cookie consent banner is displayed. Dismissing this
                banner stores a preference in your browser's local storage so the banner does not appear again during
                that session.
            </p>
            <p><strong>I do not use advertising, profiling, or third-party tracking cookies.</strong></p>

            <table>
                <thead>
                    <tr>
                        <th>Cookie</th>
                        <th>Purpose</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>XSRF-TOKEN</code></td>
                        <td>Security — prevents cross-site request forgery attacks</td>
                        <td>Essential</td>
                    </tr>
                    <tr>
                        <td><code>laravel_session</code></td>
                        <td>Maintains your login session</td>
                        <td>Essential</td>
                    </tr>
                    <tr>
                        <td><code>cookie_consent</code> (localStorage)</td>
                        <td>Remembers that you have acknowledged the cookie notice</td>
                        <td>Functional</td>
                    </tr>
                </tbody>
            </table>

            <hr class="border-stone-200 my-10">

            <h2>6. Your Rights Under UK GDPR</h2>
            <p>You have the following rights regarding your personal data:</p>
            <ul>
                <li><strong>Right of access</strong> — you can request a copy of the personal data I hold about you.</li>
                <li><strong>Right to rectification</strong> — you can request that inaccurate data be corrected.</li>
                <li><strong>Right to erasure ("right to be forgotten")</strong> — you can request that I delete your personal data. I will action this within 30 days.</li>
                <li><strong>Right to restrict processing</strong> — you can ask me to pause processing your data in certain circumstances.</li>
                <li><strong>Right to data portability</strong> — you can request your data in a structured, commonly used format.</li>
                <li><strong>Right to withdraw consent</strong> — where processing is based on consent (e.g. newsletter), you can withdraw consent at any time without affecting the lawfulness of prior processing.</li>
                <li><strong>Right to object</strong> — you can object to processing based on legitimate interests.</li>
            </ul>
            <p>
                To exercise any of these rights, please <a href="{{ route('contact') }}" class="text-amber-600 hover:text-amber-700">contact me</a> with the subject line <em>"Data Subject Request"</em>. I will respond within 30 calendar days.
            </p>
            <p>
                You also have the right to lodge a complaint with the
                <a href="https://ico.org.uk/make-a-complaint/" target="_blank" rel="noopener noreferrer" class="text-amber-600 hover:text-amber-700">Information Commissioner's Office (ICO)</a>
                if you believe your data has been handled unlawfully.
            </p>

            <hr class="border-stone-200 my-10">

            <h2>7. Newsletter Unsubscribe</h2>
            <p>
                Every newsletter email I send contains an <strong>unsubscribe link</strong> at the bottom. Clicking
                this link will immediately remove you from the mailing list.
            </p>
            <p>
                If you have a registered account on this website, you can also manage your newsletter subscription
                at any time via your
                <a href="{{ route('settings.newsletter') }}" class="text-amber-600 hover:text-amber-700">account settings</a>
                (Settings → Newsletter). You can also request removal by
                <a href="{{ route('contact') }}" class="text-amber-600 hover:text-amber-700">contacting me directly</a>.
            </p>

            <hr class="border-stone-200 my-10">

            <h2>8. Data Security</h2>
            <p>
                I take reasonable precautions to protect your personal data against unauthorised access, disclosure,
                or loss. The website is served over HTTPS. Access to stored contact messages and subscriber data is
                restricted to authenticated administrators only.
            </p>

            <hr class="border-stone-200 my-10">

            <h2>9. Third Parties</h2>
            <p>
                I do not sell, trade, or otherwise transfer your personal data to third parties. Your data may be
                processed by the hosting provider infrastructure in the course of delivering the website. The hosting
                provider acts as a data processor and is contractually bound to handle your data in accordance with
                applicable data protection law.
            </p>

            <hr class="border-stone-200 my-10">

            <h2>10. Children's Privacy</h2>
            <p>
                This website is not directed at children under the age of 13. I do not knowingly collect personal
                data from children. If you believe a child has submitted personal data through this site, please
                <a href="{{ route('contact') }}" class="text-amber-600 hover:text-amber-700">contact me</a> and I will delete it promptly.
            </p>

            <hr class="border-stone-200 my-10">

            <h2>11. Changes to This Policy</h2>
            <p>
                I may update this Privacy Policy from time to time. Any changes will be posted on this page with an
                updated revision date. I encourage you to review this page periodically to stay informed about how
                your data is protected.
            </p>

            <hr class="border-stone-200 my-10">

            <h2>12. Contact</h2>
            <p>
                If you have any questions about this Privacy Policy or how your data is handled, please get in touch
                via the <a href="{{ route('contact') }}" class="text-amber-600 hover:text-amber-700">contact page</a>.
            </p>
        </div>
    </div>
</div>
