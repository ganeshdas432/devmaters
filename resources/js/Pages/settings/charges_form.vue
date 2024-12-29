<template>
    <form @submit.prevent="submit">
      <div class="mb-4 grid grid-cols-3 gap-4">
                <div>
                    <label for="zone_id" class="block text-sm font-medium text-gray-700">Zone ID</label>
                    <input v-model="form.zone_id" type="text" id="zone_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label for="fs_per_km" class="block text-sm font-medium text-gray-700">FS Per KM</label>
                    <input v-model="form.fs_per_km" type="number" id="fs_per_km" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label for="gs_per_km" class="block text-sm font-medium text-gray-700">GS Per KM</label>
                    <input v-model="form.gs_per_km" type="number" id="gs_per_km" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
            </div>

            <!-- Second row with 3 fields -->
            <div class="mb-4 grid grid-cols-3 gap-4">
                <div>
                    <label for="p_per_km" class="block text-sm font-medium text-gray-700">P Per KM</label>
                    <input v-model="form.p_per_km" type="number" id="p_per_km" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label for="r_per_km" class="block text-sm font-medium text-gray-700">R Per KM</label>
                    <input v-model="form.r_per_km" type="number" id="r_per_km" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label for="free_delivery_on" class="block text-sm font-medium text-gray-700">Free Delivery On</label>
                    <input v-model="form.free_delivery_on" type="number" id="free_delivery_on" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
               
            </div>

            <!-- Third row with 3 fields -->
            <div class="mb-4 grid grid-cols-3 gap-4">
                <div>
                    <label for="r_per_person" class="block text-sm font-medium text-gray-700">R Per Person</label>
                    <input v-model="form.r_per_person" type="number" id="r_per_person" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label for="p_per_weight" class="block text-sm font-medium text-gray-700">P Per Weight</label>
                    <input v-model="form.p_per_weight" type="number" id="p_per_weight" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label for="p_weight" class="block text-sm font-medium text-gray-700">P Weight</label>
                    <input v-model="form.p_weight" type="number" id="p_weight" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
            </div>

            <!-- Fourth row with 2 fields -->
            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label for="tax_per" class="block text-sm font-medium text-gray-700">Tax Per</label>
                    <input v-model="form.tax_per" type="number" id="tax_per" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
            </div>
      <Button type="submit" class="btn btn-primary w-full" >Update Company Details</Button>


    </form>
  </template>
  
  <script>
import { useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';

  
  export default {
    props: {
      charge: {
        type: Object,
        default: () => ({
          zone_id: '',
          fs_per_km: '',
          gs_per_km: '',
          p_per_km: '',
          r_per_km: '',
          free_delivery_on: '',
          r_per_person: '',
          p_per_weight: '',
          p_weight: '',
          tax_per: '',
        }),
      },
    },
    setup(props) {
      const form = useForm({ ...props.charge });
  
      const submit = () => {
        if (form.id) {
          form.put(route('charges.update', form.id));
        } else {
          form.post(route('charges.store'));
        }
      };
  
      return { form, submit };
    },
  };
  </script>
  