<template>
  <div class="space-y-6">
    <!-- Configuration Selector -->
    <div class="grid grid-cols-3 gap-4">
      <!-- Wood Type -->
      <div>
        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Wood Type</label>
        <select
          v-model="selectedWood"
          class="w-full border border-gray-200 px-3 py-2 text-sm rounded-sm focus:outline-none focus:border-black transition-colors"
        >
          <option v-for="wood in availableWoods" :key="wood" :value="wood">{{ wood }}</option>
        </select>
      </div>

      <!-- Diameter -->
      <div>
        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Diameter</label>
        <select
          v-model="selectedDiameter"
          class="w-full border border-gray-200 px-3 py-2 text-sm rounded-sm focus:outline-none focus:border-black transition-colors"
        >
          <option v-for="diameter in availableDiameters" :key="diameter" :value="diameter">{{ diameter }}</option>
        </select>
      </div>

      <!-- Profile -->
      <div>
        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Edge Profile</label>
        <select
          v-model="selectedProfile"
          class="w-full border border-gray-200 px-3 py-2 text-sm rounded-sm focus:outline-none focus:border-black transition-colors"
        >
          <option v-for="profile in availableProfiles" :key="profile" :value="profile">{{ profile }}</option>
        </select>
      </div>
    </div>

    <!-- Price Display -->
    <div v-if="selectedVariation" class="bg-gray-100 rounded-sm p-4">
      <div class="grid grid-cols-3 gap-4 text-center">
        <div>
          <div class="text-xs text-gray-500 mb-1">Diameter</div>
          <div class="text-lg font-semibold">{{ selectedDiameter }}</div>
        </div>
        <div>
          <div class="text-xs text-gray-500 mb-1">Area</div>
          <div class="text-lg font-semibold">{{ calculatedArea }} m²</div>
        </div>
        <div>
          <div class="text-xs text-gray-500 mb-1">Price</div>
          <div class="text-2xl font-bold">CHF {{ selectedVariation.price }}</div>
        </div>
      </div>
      <div class="mt-3 text-xs text-center text-gray-500">
        Price per m²: CHF {{ pricePerSqm }}
      </div>
    </div>

    <!-- Profile Price Comparison -->
    <div>
      <h3 class="text-sm font-medium text-black mb-3">Profile Comparison ({{ selectedWood }}, {{ selectedDiameter }})</h3>
      <div class="grid grid-cols-2 gap-3">
        <div
          v-for="profile in availableProfiles"
          :key="profile"
          class="bg-white border rounded-sm p-3"
          :class="profile === selectedProfile ? 'border-black' : 'border-gray-200'"
        >
          <div class="text-xs text-gray-500 mb-1">{{ profile }}</div>
          <div class="text-lg font-semibold">
            CHF {{ getVariationPrice(selectedWood, selectedDiameter, profile) || '—' }}
          </div>
        </div>
      </div>
    </div>

    <!-- Wood Type Comparison -->
    <div>
      <h3 class="text-sm font-medium text-black mb-3">Wood Comparison ({{ selectedDiameter }}, {{ selectedProfile }})</h3>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-gray-200">
              <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Wood Type</th>
              <th class="text-right py-2 text-xs font-medium text-gray-500 uppercase">Price</th>
              <th class="text-right py-2 text-xs font-medium text-gray-500 uppercase">Per m²</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="wood in availableWoods"
              :key="wood"
              class="border-b border-gray-100"
              :class="wood === selectedWood ? 'bg-gray-50 font-medium' : ''"
            >
              <td class="py-2">{{ wood }}</td>
              <td class="py-2 text-right tabular-nums">
                CHF {{ getVariationPrice(wood, selectedDiameter, selectedProfile) || '—' }}
              </td>
              <td class="py-2 text-right tabular-nums text-gray-500">
                {{ getPricePerSqm(wood, selectedDiameter, selectedProfile) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Size Price Matrix -->
    <div>
      <h3 class="text-sm font-medium text-black mb-3">Size Matrix ({{ selectedWood }}, {{ selectedProfile }})</h3>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-gray-200">
              <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Diameter</th>
              <th class="text-right py-2 text-xs font-medium text-gray-500 uppercase">Area</th>
              <th class="text-right py-2 text-xs font-medium text-gray-500 uppercase">Price</th>
              <th class="text-right py-2 text-xs font-medium text-gray-500 uppercase">Per m²</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="diameter in availableDiameters"
              :key="diameter"
              class="border-b border-gray-100"
              :class="diameter === selectedDiameter ? 'bg-gray-50 font-medium' : ''"
            >
              <td class="py-2">{{ diameter }}</td>
              <td class="py-2 text-right tabular-nums">{{ getArea(diameter) }} m²</td>
              <td class="py-2 text-right tabular-nums">
                CHF {{ getVariationPrice(selectedWood, diameter, selectedProfile) || '—' }}
              </td>
              <td class="py-2 text-right tabular-nums text-gray-500">
                {{ getPricePerSqm(selectedWood, diameter, selectedProfile) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  variations: {
    type: Array,
    default: () => []
  }
});

const selectedWood = ref('Eiche');
const selectedDiameter = ref('100 cm');
const selectedProfile = ref('ohne Karniesprofil');

const availableWoods = computed(() => {
  const woods = new Set();
  props.variations.forEach(v => {
    const wood = v.attribute_values?.['Tisch rund - Holzart'];
    if (wood) woods.add(wood);
  });
  return Array.from(woods).sort();
});

const availableDiameters = computed(() => {
  const diameters = new Set();
  props.variations.forEach(v => {
    const diameter = v.attribute_values?.['Tisch rund - Durchmesser'];
    if (diameter) diameters.add(diameter);
  });
  return Array.from(diameters).sort((a, b) => parseInt(a) - parseInt(b));
});

const availableProfiles = computed(() => {
  const profiles = new Set();
  props.variations.forEach(v => {
    const profile = v.attribute_values?.['Tisch rund - Karniesprofil'];
    if (profile) profiles.add(profile);
  });
  return Array.from(profiles).sort();
});

const selectedVariation = computed(() => {
  return props.variations.find(v =>
    v.attribute_values?.['Tisch rund - Holzart'] === selectedWood.value &&
    v.attribute_values?.['Tisch rund - Durchmesser'] === selectedDiameter.value &&
    v.attribute_values?.['Tisch rund - Karniesprofil'] === selectedProfile.value
  );
});

const getArea = (diameter) => {
  const d = parseInt(diameter);
  const radius = d / 200; // convert cm to m and divide by 2
  return (Math.PI * radius * radius).toFixed(2);
};

const calculatedArea = computed(() => {
  return getArea(selectedDiameter.value);
});

const pricePerSqm = computed(() => {
  if (!selectedVariation.value?.price) return '—';
  const price = parseFloat(selectedVariation.value.price);
  const area = parseFloat(calculatedArea.value);
  return (price / area).toFixed(2);
});

const getVariationPrice = (wood, diameter, profile) => {
  const variation = props.variations.find(v =>
    v.attribute_values?.['Tisch rund - Holzart'] === wood &&
    v.attribute_values?.['Tisch rund - Durchmesser'] === diameter &&
    v.attribute_values?.['Tisch rund - Karniesprofil'] === profile
  );
  return variation?.price || null;
};

const getPricePerSqm = (wood, diameter, profile) => {
  const price = getVariationPrice(wood, diameter, profile);
  if (!price) return '—';
  const area = parseFloat(getArea(diameter));
  return (parseFloat(price) / area).toFixed(2);
};
</script>
