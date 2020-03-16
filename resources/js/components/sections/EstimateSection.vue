<style scoped>
.section:hover {
    /* background-color: #fafafa; */
}
</style>

<template>
    <div class="section p-2 mb-5">
        <small v-if="section.type == 'text'" class="text-primary mb-4">Text Section {{ section.id }}</small>
        <small v-else class="text-primary mb-4">Prices Section {{ section.id }}</small>
        
        <div class="mb-4 text-right">
            <button class="btn btn-sm btn-outline-danger mt-2" @click="remove()"><i class="icon ion-md-trash"></i> Remove</button>
        </div>

        <VueTrix v-model="text" @input="saveSectionWithDebounce()" />

        <div class="mt-2" v-if="section.type == 'prices'">
            <div class="row mt-2" v-for="(item, index) in items" :key="index">
                <div class="col-md-5">
                    <input type="text" class="form-control" placeholder="Item Description" v-model="item.description" @input="saveSectionWithDebounce()" @blur="saveSection()">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Item Duration (Optional)" v-model="item.duration" @input="saveSectionWithDebounce()" @blur="saveSection()">
                </div>
                <div class="col-md-3">
                    <input type="number" step="0.1" class="form-control" placeholder="Item Price" v-model="item.price" @input="saveSectionWithDebounce()" @blur="saveSection()">
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
        }
    },

    mounted() {
        this.init();
    },

    computed: {
        total() {
            let total = this.items.reduce((sum, item) => {
                return sum + (item.price || 0); 
            }, 0);

            return parseFloat(total);
        },

        formattedTotal() {
            return this.total.toFixed(2);
        }
    },

    methods: {

        init() {
            this.madeFirstInput = this.section.madeFirstInput;
            this.text = this.section.text;
            this.items = this.section.items;
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

            if(!this.section.id) {
                this.save();
                return;
            }

            this.update();
        },

        save() {
            let url = '/estimates/:estimate/sections';
            url = url.replace(':estimate', this.$parent.estimate);

            axios.post(url, {
                text: this.text,
                type: this.section.type,
                items: this.items,
            }).then(({data}) => {
                this.section.id = data.id;
            });
        },

        update() {
            let url = '/estimates/:estimate/sections/:section';
            url = url.replace(':estimate', this.$parent.estimate);
            url = url.replace(':section', this.section.id);

            axios.put(url, {
                text: this.text,
                items: this.items,
            });
        },

        remove() {

            if(!this.section.id) {
                this.$emit('sectionRemoved');
                return;
            }

            let url = '/estimates/:estimate/sections/:section';
            url = url.replace(':estimate', this.$parent.estimate);
            url = url.replace(':section', this.section.id);

            bootbox.confirm('Are you sure?', confirmed => {
                if(confirmed) {
                    axios.delete(url);
                    this.$emit('sectionRemoved');
                }
            });
        },

        addItem() {
            this.items.push({
                'description': '',
                'duration': '',
                'price': null,
            });
        },

        removeItem(index) {
            bootbox.confirm('Are you sure?', confirmed => {
                if(confirmed) {
                    this.items.splice(index, 1);
                    this.saveSection();
                }
            });
        }
    }
}
</script>