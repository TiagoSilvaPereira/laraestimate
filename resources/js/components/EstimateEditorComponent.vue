<template>
    <div>
        <div class="row">
            <div class="col-md-12" v-if="estimateData">
                <input type="text" class="form-control" v-model="estimateData.name" @input="updateDebounced()">
                <div class="switch-container mt-2">
                    <label class="switch">
                        <input type="checkbox" v-model="estimateData.use_name_as_title" @change="update()">
                        <span class="slider round"></span>
                    </label>
                    Use name as title?
                </div>
                <h3>Total $ {{ formattedTotal }}</h3>
                <a target="_blank" :href="'/estimates/' + estimateData.id" class="btn btn-secondary">View Estimate</a>
            </div>
        </div>

        <div class="row mt-4">

            <div class="col-sm-12">
                <draggable v-model="sections" draggable=".item" handle=".handle" @end="orderChanged()">
                    <div class="item" v-for="(section, index) in sections" :key="section.id">
                        <estimate-section 
                        :section="section" 
                        :estimate="estimate"
                        @sectionUpdated="updateSection($event, index)" @sectionRemoved="removeSection(index, 'text')"></estimate-section>
                    </div>
                </draggable>
            </div>

            <div class="col-sm-12">
                <button class="btn btn-primary" @click="addSection()">Add Text Section</button>
                <button class="btn btn-success" @click="addSection('prices')">Add Prices Section</button>
            </div>
        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable'

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
                return sum + (parseFloat(section.total) || 0); 
            }, 0);

            return parseFloat(total);
        },

        formattedTotal() {
            return this.total.toFixed(2);
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
            this.sections[index] = sectionData;
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
            }).then(data => {
                toast.success('Estimate saved successfully');
            });
        },

        calculateSectionsPositions() {
            let positions = {};

            this.sections.forEach((section, index) => positions[section.id] = index + 1);

            return positions;
        }

    }

}
</script>