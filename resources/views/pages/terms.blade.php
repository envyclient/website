@section('title', 'Terms of Service')

@extends('layouts.guest')

@section('content')
    <div class="mx-auto" style="max-width: 1280px;">
        <span class="my-4 h-8 inline-flex rounded-md shadow-sm">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs leading-4 font-medium rounded text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                Home
            </a>
        </span>
        <hr>
        <div class="flex flex-row">
            <div class="min-vh-100" style="min-width: 250px;">
                <h1 class="text-xl font-semibold my-3">Legend</h1>
                <ul class="list-disc px-7 tracking-wide">
                    <li class="underline cursor-pointer text-blue-500 pb-1">
                        <a href="#tos">Terms of Service</a>
                    </li>
                    <li class="underline cursor-pointer text-blue-500 pb-1">
                        <a href="#permitted-use">Permitted Use</a>
                    </li>
                    <li class="underline cursor-pointer text-blue-500 pb-1">
                        <a href="#rights">Rights of Envy Client</a>
                    </li>
                    <li class="underline cursor-pointer text-blue-500 pb-1">
                        <a href="#restrictions">Restrictions</a>
                    </li>
                    <li class="underline cursor-pointer text-blue-500 pb-1">
                        <a href="#payment-practices">Payment Practices</a>
                    </li>
                    <li class="underline cursor-pointer text-blue-500 pb-1">
                        <a href="#refunds">Refunds</a>
                    </li>
                    <li class="underline cursor-pointer text-blue-500 pb-1">
                        <a href="#data-collection">Data Collection</a>
                    </li>
                    <li class="underline cursor-pointer text-blue-500 pb-1">
                        <a href="#liability">Limitations of Liability</a>
                    </li>
                    <li class="underline cursor-pointer text-blue-500 pb-1">
                        <a href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <div>
                <h1 id="tos" class="text-4xl font-semibold my-3">Terms of Service</h1>
                <p>This license is a legal agreement between the Subscriber ("you") and Envy Client (“we”) for the use
                    of the
                    Software. By downloading any Envy Client files you agree to be bound by the terms and conditions of
                    this
                    license. We reserve the right to alter this agreement at any time, for any reason, without notice.
                    By
                    continuing to use Envy Client, you will be deemed to have accepted any changes.</p>

                <h2 id="permitted-use" class="text-4xl font-semibold my-3">Permitted Use</h2>
                <ul class="list-disc px-7">
                    <li>
                        Each account is tied to a physical machine. Each additional physical machine using the software
                        requires
                        an additional account with an active subscription.
                    </li>
                    <li>Envy Client may only be accessed on Microsoft Windows.</li>
                </ul>

                <h2 id="rights" class="text-4xl font-semibold my-3">Rights of Envy Client</h2>
                <ul class="list-disc px-7">
                    <li>
                        Envy Client reserves the right to modify or terminate accounts at any time, for any reason.
                    </li>
                </ul>

                <h2 id="restrictions" class="text-4xl font-semibold my-3">Restrictions</h2>
                <p class="mb-2">It is not permissible to:</p>
                <ul class="list-disc mx-7">
                    <li>Reproduce, distribute, or transfer Envy Client, or portions thereof, to any third party.</li>
                    <li>Sell, rent, lease, assign, and modify Envy Client or portions thereof.</li>
                    <li>Grant rights to any other person. Any violations of these restrictions are grounds for
                        termination.
                    </li>
                </ul>

                <h2 id="payment-practices" class="text-4xl font-semibold my-3">Payment Practices</h2>
                <p class="mb-2">Envy Client will use PayPal and WeChat Pay to receive payments.</p>
                <ul class="list-disc mx-7">
                    <li>The subscription for Envy Client will be in thirty-day billing cycles.</li>
                    <li>Renewals will occur automatically, unless cancelled.</li>
                    <li>Envy Client reserves the right to change prices at any time, for any reason.</li>
                    <li>
                        Envy Client also reserves the right to suspend service with sole discretion, even if the service
                        would be paid for due to violations of the Terms of Service.
                    </li>
                    <li>
                        Should payment not be received for an account with an active monthly billing cycle, then that
                        account
                        will lose Envy Client service access.
                    </li>
                    <li>All payments will be made in USD and CAD.</li>
                </ul>

                <h2 id="refunds" class="text-4xl font-semibold my-3">Refunds</h2>
                <p class="mb-2">Requested a refund within 24 hours of purchasing a subscription to the Software.</p>
                <ul class="list-disc px-7">
                    <li>Not have used downloaded the Software after purchasing a subscription.</li>
                    <li>Not have your account banned.</li>
                </ul>

                <h2 id="data-collection" class="text-4xl font-semibold my-3">Data Collection</h2>
                <p class="mb-2">Envy Client strives to collect as very little data as possible. However, the following
                    are
                    collected:</p>
                <ul class="list-disc px-7">
                    <li>Hardware Identification information that can and is used to identify a user’s machine.</li>
                    <li>Telemetry data consisting of how Envy Client is used.</li>
                    <li>Personal information used to create your account for Envy Client.</li>
                    <li>Discord nickname and account ID to update your role on the Envy Community Server.</li>
                </ul>
                <p class="my-2">
                    Should there be any issue regarding data collection, suspension, or termination of accounts, contact
                    <a href="mailto:contact@envyclient.com" class="text-blue-600 underline">contact@envyclient.com</a>.
                    Should there be any information you determine to be your right according to Canada’s Personal
                    Information
                    Protection and Electronic Documents Act (PIPEDA) or the European Union’s General Data Protection
                    Regulation
                    (GDPR), contact us to sort out any inquiries.
                </p>
                <p>All data collection practices are pursuant to Canadian and EU regulations.</p>

                <h2 id="liability" class="text-4xl font-semibold my-3">Limitations of Liability</h2>
                <p>
                    YOU ASSUME ALL RISK ASSOCIATED WITH THE INSTALLATION AND USE OF THE SOFTWARE. IN NO EVENT SHALL THE
                    AUTHORS
                    OR COPYRIGHT HOLDERS OF THE SOFTWARE BE LIABLE FOR CLAIMS, DAMAGES OR OTHER LIABILITY ARISING FROM,
                    OUT OF,
                    OR
                    IN CONNECTION WITH THE SOFTWARE. LICENSE HOLDERS ARE SOLELY RESPONSIBLE FOR DETERMINING THE
                    APPROPRIATENESS
                    OF
                    USE AND ASSUME ALL RISKS ASSOCIATED WITH ITS USE, INCLUDING BUT NOT LIMITED TO THE RISKS OF PROGRAM
                    ERRORS,
                    DAMAGE TO EQUIPMENT, LOSS OF DATA OR SOFTWARE PROGRAMS, OR UNAVAILABILITY OR INTERRUPTION OF
                    OPERATIONS.
                </p>

                <h2 id="contact" class="text-4xl font-semibold my-3">Contact Us and Dispute Resolution</h2>
                <p class="my-2">
                    If the above does not pertain to your inquiries, contact us at
                    <a href="mailto:contact@envyclient.com" class="text-blue-600 underline">contact@envyclient.com</a>.
                </p>
                <p class="mb-3">
                    Matter of dispute resolution may also be handled by email or even phone correspondence should
                    something
                    escalate.
                </p>
            </div>
        </div>

    </div>
@endsection
