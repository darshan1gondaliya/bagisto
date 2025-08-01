@pushOnce('scripts')
<script type="text/x-template" id="v-checkout-address-form-template">
    <div class="mt-2 max-md:mt-3">
    <!-- Hidden ID -->
    <x-shop::form.control-group class="hidden">
      <x-shop::form.control-group.control type="text"
        ::name="controlName + '.id'"
        v-model="internalAddress.id" />
    </x-shop::form.control-group>

    <!-- Company Name -->
    <x-shop::form.control-group>
      <x-shop::form.control-group.label>
        @lang('shop::app.checkout.onepage.address.company-name')
      </x-shop::form.control-group.label>
      <x-shop::form.control-group.control type="text"
        ::name="controlName + '.company_name'"
        v-model="internalAddress.company_name"
        :placeholder="trans('shop::app.checkout.onepage.address.company-name')" />
    </x-shop::form.control-group>

    <!-- First and Last Name -->
    <div class="grid grid-cols-2 gap-x-5 max-md:grid-cols-1">
      <x-shop::form.control-group>
        <x-shop::form.control-group.label class="required !mt-0">
          @lang('shop::app.checkout.onepage.address.first-name')
        </x-shop::form.control-group.label>
        <x-shop::form.control-group.control type="text"
          ::name="controlName + '.first_name'"
          v-model="internalAddress.first_name"
          rules="required" />
      </x-shop::form.control-group>

      <x-shop::form.control-group>
        <x-shop::form.control-group.label class="required !mt-0">
          @lang('shop::app.checkout.onepage.address.last-name')
        </x-shop::form.control-group.label>
        <x-shop::form.control-group.control type="text"
          ::name="controlName + '.last_name'"
          v-model="internalAddress.last_name"
          rules="required" />
      </x-shop::form.control-group>
    </div>

    <!-- Email -->
    <x-shop::form.control-group>
      <x-shop::form.control-group.label class="required !mt-0">
        @lang('shop::app.checkout.onepage.address.email')
      </x-shop::form.control-group.label>
      <x-shop::form.control-group.control type="email"
        ::name="controlName + '.email'"
        v-model="internalAddress.email"
        rules="required|email" />
    </x-shop::form.control-group>

    <!-- VAT (billing only) -->
    <template v-if="controlName === 'billing'">
      <x-shop::form.control-group>
        <x-shop::form.control-group.label>
          @lang('shop::app.checkout.onepage.address.vat-id')
        </x-shop::form.control-group.label>
        <x-shop::form.control-group.control type="text"
          ::name="controlName + '.vat_id'"
          v-model="internalAddress.vat_id" />
      </x-shop::form.control-group>
    </template>

    <!-- Street Address (with Autocomplete) -->
    <x-shop::form.control-group>
      <x-shop::form.control-group.label class="required !mt-0">
        @lang('shop::app.checkout.onepage.address.street-address')
      </x-shop::form.control-group.label>
      <x-shop::form.control-group.control type="text"
        id="autocomplete"
        v-model="internalAddress.address[0]"
        ::name="controlName + '.address.[0]'"
        rules="required|address" />
    </x-shop::form.control-group>

    <!-- Country / State / City / Postcode -->
    {!! view_render_event('bagisto.shop.checkout.onepage.address.form.address.after') !!}

<div class="grid grid-cols-2 gap-x-5 max-md:grid-cols-1">
    <!-- Country -->
    <x-shop::form.control-group class="!mb-4">
        <x-shop::form.control-group.label class="{{ core()->isCountryRequired() ? 'required' : '' }} !mt-0">
            @lang('shop::app.checkout.onepage.address.country')
        </x-shop::form.control-group.label>

        <x-shop::form.control-group.control
            type="select"
            ::name="controlName + '.country'"
            ::value="address.country"
            v-model="selectedCountry"
            rules="{{ core()->isCountryRequired() ? 'required' : '' }}"
            :label="trans('shop::app.checkout.onepage.address.country')"
            :placeholder="trans('shop::app.checkout.onepage.address.country')"
        >
            <option value="">
                @lang('shop::app.checkout.onepage.address.select-country')
            </option>

            <option
                v-for="country in countries"
                :value="country.code"
            >
                @{{ country.name }}
            </option>
        </x-shop::form.control-group.control>

        <x-shop::form.control-group.error ::name="controlName + '.country'" />
    </x-shop::form.control-group>

    {!! view_render_event('bagisto.shop.checkout.onepage.address.form.country.after') !!}

    <x-shop::form.control-group>
                    <x-shop::form.control-group.label class="{{ core()->isStateRequired() ? 'required' : '' }} !mt-0">
                        @lang('shop::app.checkout.onepage.address.state')
                    </x-shop::form.control-group.label>

                    <template v-if="states">
                        <template v-if="haveStates">
                            <x-shop::form.control-group.control
                                type="select"
                                ::name="controlName + '.state'"
                                v-model="internalAddress.state"
                                rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                ::value="address.state"
                                :label="trans('shop::app.checkout.onepage.address.state')"
                                :placeholder="trans('shop::app.checkout.onepage.address.state')"
                            >
                                <option value="">
                                    @lang('shop::app.checkout.onepage.address.select-state')
                                </option>

                                <option
                                    v-for='(state, index) in states[selectedCountry]'
                                    :value="state.code"
                                >
                                    @{{ state.default_name }}
                                </option>
                            </x-shop::form.control-group.control>
                        </template>

                        <template v-else>
                            <x-shop::form.control-group.control
                                type="text"
                                ::name="controlName + '.state'"
                                ::value="address.state"
                                rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                :label="trans('shop::app.checkout.onepage.address.state')"
                                :placeholder="trans('shop::app.checkout.onepage.address.state')"
                            />
                        </template>
                    </template>

                    <x-shop::form.control-group.error ::name="controlName + '.state'" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.state.after') !!}
            </div>

    <div class="grid grid-cols-2 gap-x-5 max-md:grid-cols-1">
      <x-shop::form.control-group>
        <x-shop::form.control-group.label class="required !mt-0">
          @lang('shop::app.checkout.onepage.address.city')
        </x-shop::form.control-group.label>
        <x-shop::form.control-group.control type="text"
          v-model="internalAddress.city"
          ::name="controlName + '.city'"
          rules="required" />
      </x-shop::form.control-group>

      <x-shop::form.control-group>
      <x-shop::form.control-group.label class="{{ core()->isPostCodeRequired() ? 'required' : '' }}">
          @lang('shop::app.checkout.onepage.address.postcode')
        </x-shop::form.control-group.label>
        <x-shop::form.control-group.control type="text"
          v-model="internalAddress.postcode"
          ::name="controlName + '.postcode'"
          rules="{{ core()->isPostCodeRequired() ? 'required' : '' }}|postcode" />
      </x-shop::form.control-group>
    </div>

    <!-- Phone -->
    <x-shop::form.control-group>
      <x-shop::form.control-group.label class="required !mt-0">
        @lang('shop::app.checkout.onepage.address.telephone')
      </x-shop::form.control-group.label>
      <x-shop::form.control-group.control type="text"
        ::name="controlName + '.phone'"
        v-model="internalAddress.phone"
        rules="required|numeric" />
    </x-shop::form.control-group>
  </div>
</script>

<script type="module">
    app.component('v-checkout-address-form', {
        template: '#v-checkout-address-form-template',

        props: {
            controlName: {
                type: String,
                required: true
            },
            address: {
                type: Object,

                default: () => ({
                    id: 0,
                    company_name: '',
                    first_name: '',
                    last_name: '',
                    email: '',
                    address: [],
                    country: '',
                    state: '',
                    city: '',
                    postcode: '',
                    phone: '',
                }),
            },
        },

        data() {
            return {
                internalAddress: JSON.parse(JSON.stringify(this.address)),
                selectedCountry: this.address.country || '',
                countries: [],
                states: {}
            };
        },

        computed: {
            haveStates() {
                return Array.isArray(this.states[this.selectedCountry]) && this.states[this.selectedCountry].length;
            }
        },

        watch: {
            address: {
                deep: true,
                immediate: true,
                handler(v) {
                    this.internalAddress = JSON.parse(JSON.stringify(v));
                    this.selectedCountry = v.country || '';
                }
            },

            selectedCountry(newVal) {
                this.internalAddress.country = newVal;
                this.$emit('update:address', this.internalAddress);
            },

            internalAddress: {
                deep: true,
                handler(v) {
                    this.$emit('update:address', v);
                }
            }
        },

        mounted() {
            this.getCountries();
            this.getStates();
            this.loadGoogleApi();
        },

        methods: {
            getCountries() {
                this.$axios.get("{{ route('shop.api.core.countries') }}")
                    .then(res => this.countries = res.data.data)
                    .catch(console.error);
            },

            getStates() {
                this.$axios.get("{{ route('shop.api.core.states') }}")
                    .then(res => this.states = res.data.data)
                    .catch(console.error);
            },

            loadGoogleApi() {
                if (window.google?.maps?.places) return this.initAutocomplete();
                const script = document.createElement('script');
                script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyBbAydQrfVtyfPCoKSGIgxqp18XT6WX_qU&libraries=places`;
                script.async = true;
                script.defer = true;
                script.onload = this.initAutocomplete;
                document.head.appendChild(script);
            },

            initAutocomplete() {
                this.$nextTick(() => {
                    const input = document.getElementById('autocomplete');
                    if (!input) return setTimeout(this.initAutocomplete, 300);
                    input.addEventListener('keydown', e => {
                        if (e.key === 'Enter') e.preventDefault();
                    });
                    const ac = new google.maps.places.Autocomplete(input, {
                        componentRestrictions: {
                            country: 'in'
                        },
                        fields: ['address_components', 'formatted_address', 'geometry', 'name']
                    });
                    ac.addListener('place_changed', () => {
                        const place = ac.getPlace();
                        this.applyPlace(place);
                    });
                });
            },

            applyPlace(place) {
                if (!place?.address_components) return;

                const comp = (type) => {
                    const c = place.address_components.find(x => x.types.includes(type));
                    return c ? c.long_name : '';
                };

                const compShort = (type) => {
                    const c = place.address_components.find(x => x.types.includes(type));
                    return c ? c.short_name : '';
                };

                // Extract detailed address parts
                const streetNumber = comp('street_number'); // e.g., "12"
                const route = comp('route'); // e.g., "Palm Street"
                const sublocality = comp('sublocality_level_1'); // e.g., "South Bopal"
                const premise = comp('premise'); // e.g., "Krish Exotica"
                const neighborhood = comp('neighborhood'); // fallback if premise missing
                const block = comp('sublocality_level_2'); // e.g., "Block-M"

                // Combine address nicely
                const addressParts = [
                    block,
                    premise || neighborhood,
                    sublocality,
                    `${streetNumber} ${route}`.trim()
                ].filter(Boolean); // remove empty strings

                this.internalAddress.address[0] = addressParts.join(', '); // "Block-M, Krish Exotica, South Bopal, 12 Palm Street"

                this.internalAddress.city =
                    comp('locality') || comp('administrative_area_level_2');
                this.internalAddress.postcode = comp('postal_code');
                this.internalAddress.state = compShort('administrative_area_level_1'); // <-- use short code like "GJ"
                this.selectedCountry = compShort('country');
            }
        }
    });
</script>
<style>
    .pac-container {
        z-index: 10000 !important;
        font-family: inherit;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        border-radius: 4px;
    }

    .pac-item {
        padding: 8px 10px;
        cursor: pointer;
        border-bottom: 1px solid #f5f5f5;
        font-size: 14px;
    }

    .pac-item:hover {
        background-color: #f1f1f1;
    }

    .pac-item-query {
        font-size: 14px;
        color: #333;
        font-weight: bold;
    }

    .pac-icon {
        margin-right: 8px;
        color: #666;
    }
</style>
@endPushOnce