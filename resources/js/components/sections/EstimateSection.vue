<style scoped>
.section:hover {
    /* background-color: #fcfcfc; */
}
</style>

<template>
    <div class="section p-2 mb-5" v-if="sectionData">
        
        <small v-if="sectionData.type == 'text'" class="text-primary mb-4">{{ trans.get('app.text_section') }}</small>
        <small v-else class="text-primary mb-4">{{ trans.get('app.prices_section') }}</small>
        
        <div class="mb-4 text-right">
            <div>
                <small v-if="saving">{{ trans.get('app.saving') }}</small>
                <small v-else>{{ trans.get('app.all_changes_are_saved') }}</small>
            </div>
            <button class="btn btn-sm btn-outline-secondary mt-2 handle" :disabled="!sectionData.id"><i class="icon ion-md-move"></i> {{ trans.get('app.labels.move') }}</button>
            <button class="btn btn-sm btn-outline-danger mt-2" @click="remove()"><i class="icon ion-md-trash"></i> {{ trans.get('app.labels.remove') }}</button>
        </div>

        <VueTrix v-model="sectionData.text" @input="saveSectionWithDebounce()" placeholder="Add tour section content here. You can use *TOTAL_PRICE* to show the estimate total price in any place, and *TOTAL_SELECTED_PRICE* to show the total selected price." />

        <div class="mt-2" v-if="sectionData.type == 'prices'">
            <div class="row mt-2" v-for="(item, index) in sectionData.items" :key="index">
                <div class="col-md-5">
                    <input type="text" class="form-control" :placeholder="trans.get('app.item_description')" v-model="item.description" @input="saveSectionWithDebounce()" @blur="saveSection()">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" :placeholder="trans.get('app.item_duration')" v-model="item.duration" @input="saveSectionWithDebounce()" @blur="saveSection()">
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.1" class="form-control" :placeholder="trans.get('app.item_price')" v-model="item.price" @input="saveSectionWithDebounce()" @blur="saveSection()">
                </div>
                <div class="col-md-2">
                    <div class="switch-container">
                        <label class="switch">
                            <input type="checkbox" v-model="item.obligatory" @change="saveSection()">
                            <span class="slider round"></span>
                        </label>
                        {{ trans.get('app.obligatory') }}
                    </div>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-sm btn-outline-danger mt-2" @click="removeItem(index)"><i class="icon ion-md-remove"></i></button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3 offset-md-8 text-right">
                    <b>{{ trans.get('app.labels.total') }} {{ formattedTotal }}</b>
                </div>
            </div>
            <button class="btn btn-sm btn-outline-primary mt-2" @click="addItem()"><i class="icon ion-md-add"></i> {{ trans.get('app.add_item') }}</button>
        </div>

        <hr class="mt-4">
    </div>
</template>

<script>
import VueTrix from "vue-trix";

export default {
    components: {
        VueTrix
    },

    props: ['estimate', 'section', 'currencySettings'],

    data() {
        return {
            saving: false,
            madeFirstInput: false,
            text: null,
            items: [],
            addingItem: false,
            sectionData: null,
        }
    },

    mounted() {
        this.init();
    },

    computed: {
        total() {
            let total = this.sectionData.items.reduce((sum, item) => {
                return sum + (parseFloat(item.price) || 0); 
            }, 0);

            total = parseFloat(total);

            this.sectionData.total = total;

            return total;
        },

        formattedTotal() {
            return this.currencySettings.symbol + ' ' + formatMoney(
                this.total,
                2,
                this.currencySettings.decimal_separator,
                this.currencySettings.thousands_separator,
            );
        }
    },

    watch: {
        sectionData() {
            this.$emit('sectionUpdated', this.sectionData);
        }
    },

    methods: {

        init() {
            this.sectionData = this.section;
            this.madeFirstInput = (this.sectionData.madeFirstInput != undefined) ? this.sectionData.madeFirstInput : true;
        },

        saveSectionWithDebounce: _.debounce(function() {
            this.saveSection();
        }, 300),

        saveSection() {
            if(!this.madeFirstInput) {
                this.madeFirstInput = true;
                return;
            }
            
            this.showSavingLabel();

            if(!this.sectionData.id) {
                this.save();
                return;
            }

            this.update();
        },

        save() {
            let url = '/estimates/:estimate/sections';
            url = url.replace(':estimate', this.estimate);

            axios.post(url, {
                text: this.sectionData.text,
                type: this.sectionData.type,
                items: this.sectionData.items,
            }).then(({data}) => {
                this.sectionData.id = data.id;
            });
        },

        update() {
            let url = '/estimates/:estimate/sections/:section';
            url = url.replace(':estimate', this.estimate);
            url = url.replace(':section', this.sectionData.id);

            axios.put(url, {
                text: this.sectionData.text,
                items: this.sectionData.items,
            });
        },

        remove() {

            if(!this.sectionData.id) {
                this.$emit('sectionRemoved');
                return;
            }

            let url = '/estimates/:estimate/sections/:section';
            url = url.replace(':estimate', this.estimate);
            url = url.replace(':section', this.sectionData.id);

            bootbox.confirm('Are you sure?', confirmed => {
                if(confirmed) {
                    axios.delete(url);
                    this.$emit('sectionRemoved');
                }
            });
        },

        addItem() {
            this.sectionData.items.push({
                'description': '',
                'duration': '',
                'price': null,
                'obligatory': false,
            });
        },

        removeItem(index) {
            bootbox.confirm('Are you sure?', confirmed => {
                if(confirmed) {
                    this.sectionData.items.splice(index, 1);
                    this.saveSection();
                }
            });
        },

        showSavingLabel() {
            this.saving = true;

            setTimeout(() => {
                this.saving = false;
            }, 500);
        },
    }
}
</script>