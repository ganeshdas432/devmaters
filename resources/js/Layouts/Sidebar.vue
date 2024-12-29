<template>
  <PanelMenu :model="items" class="w-150">
    <template #item="{ item }">
      <!-- Internal Links -->
      <a v-if="item.route" v-ripple
        class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2"
        :href="route(item.route)" :target="item.target" :class="{
          'bg-primary text-white rounded': route().current(item.route) || route().current().startsWith(item.prefix)
        }">
        <span :class="item.icon" />
        <span class="ml-2">{{ item.label }}</span>
        <span v-if="item.items" class="pi pi-angle-down text-primary ml-auto" />
      </a>

      <!-- External Links -->
      <a v-else v-ripple class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2"
        :href="item.url" :target="item.target">
        <span :class="item.icon" />
        <span class="ml-2">{{ item.label }}</span>
        <span v-if="item.items" class="pi pi-angle-down text-primary ml-auto" />
      </a>
    </template>
  </PanelMenu>
</template>

<script setup>
import { ref } from 'vue';
import PanelMenu from 'primevue/panelmenu';

// Define menu items
const items = ref([
  { label: 'Dashboard', icon: 'pi pi-palette', route: 'dashboard', prefix: 'dashboard' },
  { label: 'Customers', icon: 'pi pi-users', route: 'customerlist', prefix: 'customer' },
  { label: 'Vendors', icon: 'pi pi-warehouse', route: 'vendorlist', prefix: 'vendor' },
  { label: 'Riders', icon: 'pi pi-car', route: 'riderlist', prefix: 'rider' },
  { label: 'Shops', icon: 'pi pi-shop', route: 'shoplist', prefix: 'shop' },
  { label: 'Product', icon: 'pi pi-box', route: 'productlist', prefix: 'product' },
  { label: 'Orders', icon: 'pi pi-shopping-cart', route: 'orders', prefix: 'order' },
  { label: 'Deliveries', icon: 'pi pi-truck', route: 'deliveries', prefix: 'delivery' },
  { label: 'Ratings', icon: 'pi pi-star', route: 'ratings', prefix: 'rating' },
  { label: 'Settings', icon: 'pi pi-wrench', route: 'settings.index', prefix: 'setting' }
]);
</script>

<style scoped>
/* Selected menu item styles */
.bg-primary {
  background-color: var(--p-emerald-500);
  /* Ensure --primary-color is defined */
}

.text-white {
  color: white;
}

.rounded {
  border-radius: 8px;
  /* Adjust the radius as needed */
}



/* Optional: Deep selector to modify PanelMenu styles */
::v-deep(.p-panelmenu-panel) {
  --p-panelmenu-panel-padding: 0px;
}
</style>
