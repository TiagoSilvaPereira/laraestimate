<style>
    .fixed-buttons {
        position: fixed;
        top: 0;
        right: 0;
        z-index: 1030;
    }

    #estimateMainSection {
        min-height: 100vh;
        background-color: #eee;
        text-align: justify;
    }

    #estimateMainSection h1 {
        text-align: left !important;
        font-size: 1.5rem;
    }

    #estimateMainSection tr.item:not(.selected) {
        color: #ccc;
        text-decoration: line-through;
    }

    #estimateMainSection input[type="checkbox"] {
        width: 1.5em;
        height: 1.5em;
    }
</style>

<template>
    <main id="estimateMainSection" class="p-md-5">

        <div class="modal fade" id="shareEstimateModal" v-if="estimateData">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ trans.get('app.share_estimate') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="link">{{ trans.get('app.copy_this_link') }}</label>
                        <input ref="shareableUrl" type="text" class="form-control" :value="estimateData.share_url"  @click="copyToClipboard">

                        <template v-if="canShareEmail">
                            <label for="link" class="mt-4">{{ trans.get('app.or_send_an_email') }}</label>
                            <input type="email" class="form-control" :placeholder="trans.get('app.type_email_address_here')" v-model="shareEmail">

                            <span class="mt-2 text-primary" v-show="sendingEmail">{{ trans.get('app.sending_email') }}</span>

                            <button class="btn btn-primary mt-4 float-right" :disabled="sendingEmail" @click="sendEmail()"><i class="icon ion-md-mail"></i> {{ trans.get('app.labels.send') }}</button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed-buttons p-4 text-right" v-if="estimateData">
            <button class="btn btn-primary d-print-none" @click="print()"><i class="icon ion-md-print"></i> {{ trans.get('app.labels.print') }}</button>
            <button class="btn btn-success d-print-none" @click="openShareModal()"><i class="icon ion-md-share"></i> {{ trans.get('app.labels.share') }}</button>
        </div>

        <div class="container" id="printContainer">

            <div class="row">

                <div id="estimateDocument" class="col-md-8 offset-md-2 bg-white p-md-5 animated bounceIn fast" v-if="estimateData">

                    <section class="m-4 text-center" v-if="estimateData.logo_image">
                        <img :src="estimateData.logo_image" alt="Estimate Image" width="150px">
                    </section>

                    <section class="mb-5" v-if="estimateData.use_name_as_title">
                        <h1><b>{{ estimateData.name }}</b></h1>
                    </section>

                    <section class="mb-5" v-for="section in estimateData.sections" :key="section.id">
                        <p v-html="section.presentable_text"></p>
                        
                        <div class="table-responsive">
                            <table class="table mt-4 p-sm-2 p-md-0" v-if="section.items.length">
                                <tr>
                                    <th></th>
                                    <th>{{ trans.get('app.description') }}</th>
                                    <th>{{ trans.get('app.duration') }}</th>
                                    <th class="text-right">{{ trans.get('app.price') }}</th>
                                </tr>

                                <tr v-for="item in section.items" :key="item.id" class="item" :class="{'selected': item.selected}">
                                    <td><input class="check-item" type="checkbox" v-if="!item.obligatory" v-model="item.selected" @change="renderPrices()"></td>
                                    <td>{{ item.description || '-' }}</td>
                                    <td>{{ item.duration || '-' }}</td>
                                    <td class="text-right">{{ formattedPrice(item.price) || '-' }}</td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="text-right"><b>{{ trans.get('app.section_total') }}:</b></td>
                                    <td class="text-right">{{ formattedPrice(sectionTotal(section)) }}</td>
                                </tr>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {

    props: ['estimate', 'canShareEmail'],

    data() {
        return {
            shareEmail: '',
            sendingEmail: false,
            estimateData: null,
        }
    },

    created() {
        window.addEventListener('beforeprint', this.preparePrintMode);
        window.addEventListener('afterprint', this.returnToViewMode);
    },

    mounted() {
        this.init();
    },

    computed: {

        estimateTotalPrice() {
            if(!this.estimateData.sections) return 0;

            let total = this.estimateData.sections.reduce((sum, section) => {
                return sum + this.sectionTotal(section, false); 
            }, 0);

            return total;
        },

        estimateTotalSelectedPrice() {
            if(!this.estimateData.sections) return 0;
            
            let total = this.estimateData.sections.reduce((sum, section) => {
                return sum + this.sectionTotal(section, true); 
            }, 0);

            return total;
        }

    },

    methods: {

        init() {
            axios.get('/estimates/' + this.estimate + '/data').then(({data}) => {
                this.estimateData = this.treatData(data);
                
                this.$nextTick(() => {
                    this.renderPrices();
                })
            });
        },

        treatData(data) {
            data.sections = data.sections.map(section => {
                
                section.items = section.items.map(item => { 
                    item.selected = true ;
                    return item;
                });
                
                return section;
            });

            return data;
        },

        sectionTotal(section, onlySelected = true) {
            let total = section.items.reduce((sum, item) => {
                let itemPrice = (parseFloat(item.price) || 0);

                if(onlySelected) {
                    itemPrice = !item.selected ? 0 : itemPrice;
                }

                return sum + itemPrice; 
            }, 0);

            return parseFloat(total);
        },

        formattedPrice(price) {
            let currencySettings = this.estimateData.currency_settings;

            return currencySettings.symbol + ' ' + formatMoney(
                price, 2, 
                currencySettings.decimal_separator,
                currencySettings.thousands_separator,
            ).toString();
        },

        renderPrices() {
            let totalPriceElements = document.querySelectorAll('.total-calc-price');
            let totalSelectedPriceElements = document.querySelectorAll('.total-selected-calc-price');

            totalPriceElements.forEach(priceElement => {
                priceElement.innerHTML = this.formattedPrice(this.estimateTotalPrice);
            });

            totalSelectedPriceElements.forEach(priceElement => {
                priceElement.innerHTML = this.formattedPrice(this.estimateTotalSelectedPrice);
            });
        },

        openShareModal() {
            $('#shareEstimateModal').modal('show');
        },

        copyToClipboard() {
            var copyText = this.$refs.shareableUrl;

            copyText.select();
            copyText.setSelectionRange(0, 99999);

            document.execCommand('copy');

            toast.success('Link copied successfully');
        },

        sendEmail() {
            this.sendingEmail = true;

            axios.post('/estimates/' + this.estimate + '/share', {
                'email': this.shareEmail
            }).then(() => {
                this.sendingEmail = false;
                toast.success('E-mail sent successfully');
            }).catch(error => {
                this.sendingEmail = false;
                treatAxiosError(error);
            })
        },

        print() {
            window.print();
        },

        preparePrintMode() {
            /**
             * Wee need to manipulate the DOM here because VueJs can't
             * update the VirtualDOM on beforeprint event
             */
            let container = document.getElementById('printContainer'),
                estimate = document.getElementById('estimateDocument'),
                mainElement = document.getElementById('estimateMainSection');
            
            container.classList.remove('container');
            container.classList.add('container-fluid');
            estimate.classList.add('col');
            estimate.classList.remove('col-md-8');
            estimate.classList.remove('offset-md-2');

            mainElement.classList.add('bg-white');
        },

        returnToViewMode() {
            let container = document.getElementById('printContainer'),
                estimate = document.getElementById('estimateDocument'),
                mainElement = document.getElementById('estimateMainSection');
            
            container.classList.add('container');
            container.classList.remove('container-fluid');
            estimate.classList.remove('col');
            estimate.classList.add('col-md-8');
            estimate.classList.add('offset-md-2');

            mainElement.classList.remove('bg-white');
        }

    }

}
</script>