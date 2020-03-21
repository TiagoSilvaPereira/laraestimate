<style scoped>
.section:hover {
    /* background-color: #fcfcfc; */
}
</style>

<template>
    <div class="section p-2 mb-5" v-if="sectionData">
        <small v-if="sectionData.type == 'text'" class="text-primary mb-4">Text Section {{ sectionData.id }}</small>
        <small v-else class="text-primary mb-4">Prices Section {{ sectionData.id }}</small>
        
        <div class="mb-4 text-right">
            <button class="btn btn-sm btn-outline-danger mt-2" @click="remove()"><i class="icon ion-md-trash"></i> Remove</button>
        </div>

        <VueTrix v-model="sectionData.text" @input="saveSectionWithDebounce()" placeholder="Add tour section content here. You can use *TOTAL_PRICE* to show the estimate total price in any place, and *TOTAL_SELECTED_PRICE* to show the total selected price." />

        <div class="mt-2" v-if="sectionData.type == 'prices'">
            <div class="row mt-2" v-for="(item, index) in sectionData.items" :key="index">
                <div class="col-md-5">
                    <input type="text" class="form-control" placeholder="Item Description" v-model="item.description" @input="saveSectionWithDebounce()" @blur="saveSection()">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" placeholder="Item Duration (Optional)" v-model="item.duration" @input="saveSectionWithDebounce()" @blur="saveSection()">
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.1" class="form-control" placeholder="Item Price" v-model="item.price" @input="saveSectionWithDebounce()" @blur="saveSection()">
                </div>
                <div class="col-md-2">
                    <div class="switch-container">
                        <label class="switch">
                            <input type="checkbox" v-model="item.obligatory" @change="saveSection()">
                            <span class="slider round"></span>
                        </label>
                        Obligatory?
                    </div>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-sm btn-outline-danger mt-2" @click="removeItem(index)"><i class="icon ion-md-remove"></i></button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3 offset-md-8 text-right">
                    <b>Total $ {{ formattedTotal }}</b>
                </div>
            </div>
            <button class="btn btn-sm btn-outline-primary mt-2" @click="addItem()"><i class="icon ion-md-add"></i> Add Item</button>
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

    props: ['section'],

    data() {
        return {
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
            return this.total.toFixed(2);
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
            
            this.$parent.showSavingLabel();

            if(!this.sectionData.id) {
                this.save();
                return;
            }

            this.update();
        },

        save() {
            let url = '/estimates/:estimate/sections';
            url = url.replace(':estimate', this.$parent.estimate);

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
            url = url.replace(':estimate', this.$parent.estimate);
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
            url = url.replace(':estimate', this.$parent.estimate);
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
        }
    }
}
</script>