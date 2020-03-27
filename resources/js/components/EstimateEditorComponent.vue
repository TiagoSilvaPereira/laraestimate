<style scoped>
    .total-viewer {
        display: inline-block;
        align-items: center;
        background: #eee;
        border: 1px solid #ccc;
        padding: 1.3em;
    }

    .total-viewer h3 {
        margin: 0px;
    }
</style>
<template>
    <div>
        <div class="row" v-if="estimateData">
            <div class="form-group col-md-9">
                <label>{{ trans.get('app.labels.name') }}</label>
                <input type="text" class="form-control" v-model="estimateData.name" @input="updateDebounced()">
            </div>

            <div class="form-group col-md-3 d-flex align-items-end">
                <div class="switch-container">
                    <label class="switch">
                        <input type="checkbox" v-model="estimateData.use_name_as_title" @change="update()">
                        <span class="slider round"></span>
                    </label>
                    {{ trans.get('app.use_name_as_title') }}
                </div>
            </div>

            <div class="form-group col-md-4">
                <label for="currency_symbol">{{ trans.get('app.currency_symbol') }}</label>
                <input type="text" class="form-control" v-model="estimateData.currency_settings.symbol" @input="updateDebounced()">
            </div>

            <div class="form-group col-md-4">
                <label for="currency_decimal_separator">{{ trans.get('app.currency_decimal_separator') }}</label>
                <input type="text" class="form-control" v-model="estimateData.currency_settings.decimal_separator" @input="updateDebounced()">
            </div>

            <div class="form-group col-md-4">
                <label for="currency_thousands_separator">{{ trans.get('app.currency_thousands_separator') }}</label>
                <input type="text" class="form-control" v-model="estimateData.currency_settings.thousands_separator" @input="updateDebounced()">
            </div>

            <div class="col-md-12 text-right">
                <div class="total-viewer">
                    <h3>{{ trans.get('app.labels.total') }} {{ formattedTotal }}</h3>
                </div>
            </div>
        </div>
        
        <hr>

        <div class="row mt-4">

            <div class="col-sm-12 mt-5">
                <h5>{{ trans.get('app.estimate_content') }}</h5>
            </div>

            <div class="col-sm-12 mt-4">
                <draggable v-model="sections" draggable=".item" handle=".handle" @end="orderChanged()">
                    <div class="item" v-for="(section, index) in sections" :key="section.id">
                        <estimate-section 
                        :section="section" 
                        :estimate="estimate"
                        :currencySettings="estimateData.currency_settings"
                        @sectionUpdated="updateSection($event, index)" @sectionRemoved="removeSection(index, 'text')"></estimate-section>
                    </div>
                </draggable>
            </div>

            <div class="col-sm-12">
                <button class="btn btn-primary" @click="addSection()"><i class="icon ion-md-document"></i> {{ trans.get('app.add_text_section') }}</button>
                <button class="btn btn-success" @click="addSection('prices')"><i class="icon ion-md-cash"></i> {{ trans.get('app.add_prices_section') }}</button>
            </div>
        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable';

export default {
    props: ['estimate'],
    
    components: {
        draggable,
    },
    
    data() {
        return {
            saving: false,
            sections: [],
            estimateData: null,
        }
    },

    mounted() {
        this.init();
    },

    computed: {
        total() {
            let total = this.sections.reduce((sum, section) => {
                return sum + parseFloat(section.total || 0); 
            }, 0);

            return parseFloat(total);
        },

        formattedTotal() {
            return this.formatMoney(this.total);
        }
    },

    methods: {

        init() {
            this.getEstimate();
        },

        getEstimate() {
            let url = '/estimates/:estimate/data';
            url = url.replace(':estimate', this.estimate);

            axios.get(url).then(({data}) => {
                this.estimateData = data;
                this.getSections();
            });


        },

        getSections() {
            let url = '/estimates/:estimate/sections';
            url = url.replace(':estimate', this.estimate);

            axios.get(url).then(({data}) => {
                this.sections = data;
            });
        },

        addSection(type = 'text') {
            this.sections.push({
                'id': null,
                'text': '',
                'type': type,
                'madeFirstInput': true,
                'items': [],
                'total': 0,
            });
        },

        showSavingLabel() {
            this.saving = true;

            setTimeout(() => {
                this.saving = false;
            }, 500);
        },

        updateSection(sectionData, index) {
            this.$set(this.sections, index, sectionData);
            
            let total = this.sections.reduce((sum, section) => {
                return sum + parseFloat(section.total || 0); 
            }, 0)
        },

        removeSection(index, type) {
            this.sections.splice(index, 1);
        },

        updateDebounced: _.debounce(function() {
            this.update();
        }, 300),

        orderChanged() {
            this.update();
        },

        update() {
            let url = '/estimates/:estimate';
            url = url.replace(':estimate', this.estimate);

            axios.put(url, {
                name: this.estimateData.name,
                use_name_as_title: this.estimateData.use_name_as_title,
                sections_positions: this.calculateSectionsPositions(),
                currency_symbol: this.estimateData.currency_settings.symbol,
                currency_decimal_separator: this.estimateData.currency_settings.decimal_separator,
                currency_thousands_separator: this.estimateData.currency_settings.thousands_separator,
            }).then(data => {
                toast.success('Estimate saved successfully');
            });
        },

        calculateSectionsPositions() {
            let positions = {};

            this.sections.forEach((section, index) => positions[section.id] = index + 1);

            return positions;
        },

        formatMoney(money) {
            if(!this.estimateData) return '-';

            let currencySettings = this.estimateData.currency_settings;
            
            return currencySettings.symbol + ' ' + formatMoney(
                money, 
                2, 
                currencySettings.decimal_separator, 
                currencySettings.thousands_separator
            ).toString();
        }

    }

}
</script>