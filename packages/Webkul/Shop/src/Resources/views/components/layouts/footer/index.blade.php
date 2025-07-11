{!! view_render_event('bagisto.shop.layout.footer.before') !!}

<!--
    The category repository is injected directly here because there is no way
    to retrieve it from the view composer, as this is an anonymous component.
-->
@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

<!--
    This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.
-->
@php
$channel = core()->getCurrentChannel();

$customization = $themeCustomizationRepository->findOneWhere([
'type' => 'footer_links',
'status' => 1,
'theme_code' => $channel->theme,
'channel_id' => $channel->id,
]);
@endphp

<footer class="mt-9 bg-blue desktop-footer max-sm:mt-10">
    <div class="flex justify-between gap-x-6 gap-y-8 p-[60px] max-1060:flex-col-reverse max-md:gap-5 max-md:p-8 max-sm:px-4 max-sm:py-5">
        <!-- For Desktop View -->
        <div class="flex flex-wrap items-start gap-24 max-1180:gap-6 max-1060:hidden">

            @if ($customization?->options)
            @foreach ($customization->options as $footerLinkSection)

            <ul class="grid gap-5 text-sm">
                @php
                usort($footerLinkSection, function ($a, $b) {
                return $a['sort_order'] - $b['sort_order'];
                });
                @endphp
                <h5 class="text-lg font-semibold txt-white">INFORMATION</h5>
                @foreach ($footerLinkSection as $link)
                <li>
                    <a href="{{ $link['url'] }}">
                        {{ $link['title'] }}
                    </a>
                </li>
                @endforeach
            </ul>
            @endforeach
            @endif
        </div>

        <!-- For Mobile view -->
        <div class="hidden !w-full rounded-xl  max-1060:block ">

            <div class="flex justify-between !bg-transparent">
                @if ($customization?->options)
                @foreach ($customization->options as $footerLinkSection)
                <ul class="grid gap-5 text-sm">
                    @php
                    usort($footerLinkSection, function ($a, $b) {
                    return $a['sort_order'] - $b['sort_order'];
                    });
                    @endphp
                    <h5 class="text-lg font-semibold txt-white">INFORMATION</h5>
                    @foreach ($footerLinkSection as $link)
                    <li>
                        <a
                            href="{{ $link['url'] }}"
                            class="text-sm font-medium max-sm:text-xs">
                            {{ $link['title'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endforeach
                @endif
            </div>
        </div>
        <!-- TOP‑LEVEL NAV (first 7 categories only) -->
        @php
        $footerCategories = app('Webkul\Category\Repositories\CategoryRepository')
        ->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id)
        ->take(7);
        @endphp

        <!-- ───── Footer Category Column ─────────────────────────────────────────── -->
        <div class="footer-categories text-white">
            <h5 class="text-lg font-semibold mb-3">CATEGORIES</h5>

            <ul class="grid gap-2 text-sm leading-tight">
                @foreach ($footerCategories as $cat)
                <li>
                    <a href="{{ $cat->url }}" class="hover:text-blue-400">
                        {{ $cat->name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Social Media & Contact Info Section -->
        <div class="footer-social-contact max-1060:w-full grid gap-4 text-white">
            <div class="footer-info-col" data-section-type="footer-webPages">
                <h5 class="text-lg font-semibold mb-3">CONNECT WITH US</h5>

                <ul class="flex gap-6 items-center text-2xl">

                    <!-- Facebook -->
                    <li>
                        <a href="https://www.facebook.com/profile.php?id=61578162117924"
                            target="_blank"
                            aria-label="Visit our Facebook page"
                            title="Facebook">
                            <i class="fab fa-facebook-f fa-xl" aria-hidden="true"></i>
                        </a>
                    </li>

                    <!-- Instagram -->
                    <li>
                        <a href="https://www.instagram.com/your-profile"
                            target="_blank"
                            aria-label="Visit our Instagram profile"
                            title="Instagram"
                            class="hover:text-pink-500">
                            <i class="fab fa-instagram fa-xl" aria-hidden="true"></i>
                        </a>
                    </li>

                    <!-- X (Twitter) -->
                    <li>
                        <a href="https://x.com/NeonTechEarth"
                            target="_blank"
                            aria-label="Visit us on X (formerly Twitter)"
                            title="X (Twitter)"
                            class="hover:text-sky-400">
                            <i class="fab fa-x-twitter fa-xl" aria-hidden="true"></i>
                        </a>
                    </li>

                    <!-- LinkedIn -->
                    <li>
                        <a href="https://www.linkedin.com/in/neon-tech-earth-solutions-410562373/"
                            target="_blank"
                            aria-label="Visit our LinkedIn page"
                            title="LinkedIn"
                            class="hover:text-blue-400">
                            <i class="fab fa-linkedin-in fa-xl" aria-hidden="true"></i>
                        </a>
                    </li>

                </ul>



                <div class="mt-4 text-sm">
                    <p class="flex items-center gap-2">
                        <i class="fas fa-phone fa-rotate-90"></i>
                        <a href="tel:+918320397049" class="hover:underline">+91 8320397049</a>
                    </p>
                    <p class="flex items-center gap-2">
                        <i class="fas fa-phone fa-rotate-90"></i>
                        <a href="tel:+919687070881" class="hover:underline">+91 9687070881</a>
                    </p>
                    <p class="flex items-center gap-2 mt-1">
                        <i class="fa fa-envelope"></i>
                        <a href="mailto:support@neontechearth.com" class="hover:underline">support@neontechearth.com</a>
                    </p>
                </div>
            </div>
        </div>


        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.before') !!}

        <!-- News Letter subscription -->
        @if (core()->getConfigData('customer.settings.newsletter.subscription'))
        <div class="grid gap-2.5">
            <p
                class="max-w-[288px] txt-white text-3xl italic leading-[45px] text-navyBlue max-md:text-2xl max-sm:text-lg"
                role="heading"
                aria-level="2">
                @lang('shop::app.components.layouts.footer.newsletter-text')
            </p>

            <p class="text-xs txt-white">
                @lang('shop::app.components.layouts.footer.subscribe-stay-touch')
            </p>

            <div>
                <x-shop::form
                    :action="route('shop.subscription.store')"
                    class="mt-2.5 rounded max-sm:mt-0">
                    <div class="relative w-full">
                        <x-shop::form.control-group.control
                            type="email"
                            class="block w-[420px] bg-zinc-100 max-w-full rounded-xl border-2 border-[#e9decc] bg-[#F1EADF] px-5 py-4 text-base max-1060:w-full max-md:p-3.5 max-sm:mb-0 max-sm:rounded-lg max-sm:border-2 max-sm:p-2 max-sm:text-sm"
                            name="email"
                            rules="required|email"
                            label="Email"
                            :aria-label="trans('shop::app.components.layouts.footer.email')"
                            placeholder="email@example.com" />

                        <x-shop::form.control-group.error control-name="email" />

                        <button
                            type="submit"
                            class="absolute top-1.5 bg-blue txt-white flex w-max items-center rounded-xl bg-white px-7 py-2.5 font-medium max-md:top-1 max-md:px-5 max-md:text-xs max-sm:mt-0 max-sm:rounded-lg max-sm:px-4 max-sm:py-2 ltr:right-2 rtl:left-2">
                            @lang('shop::app.components.layouts.footer.subscribe')
                        </button>
                    </div>
                </x-shop::form>
            </div>
        </div>
        @endif

        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.after') !!}
    </div>

    <div class="flex justify-between copyright bg-[#0B1F36] px-[60px] py-3.5 max-md:justify-center max-sm:px-5">
        {!! view_render_event('bagisto.shop.layout.footer.footer_text.before') !!}

        <p class="text-sm text-zinc-600 max-md:text-center">
            @lang('shop::app.components.layouts.footer.footer-text', ['current_year'=> date('Y') ])
        </p>

        {!! view_render_event('bagisto.shop.layout.footer.footer_text.after') !!}
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}