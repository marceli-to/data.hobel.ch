<template>
  <div class="space-y-6">
    <!-- Price Calculator -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
      <h3 class="font-semibold text-lg mb-3">Price Calculator</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium mb-1">Wood Type</label>
          <select v-model="selectedWood" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            <option v-for="wood in availableWoodTypes" :key="wood" :value="wood">{{ wood }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Width</label>
          <select v-model="selectedWidth" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            <option v-for="width in availableWidths" :key="width" :value="width">{{ width }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Length</label>
          <select v-model="selectedLength" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            <option v-for="length in availableLengths" :key="length" :value="length">{{ length }}</option>
          </select>
        </div>
      </div>
      <div v-if="calculatedPrice" class="bg-yellow-100 rounded-lg p-4 border border-yellow-300 text-yellow-900">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-center">
          <div>
            <div class="text-xs mb-1">Area</div>
            <div class="text-lg font-bold">{{ calculatedPrice.area }} m²</div>
          </div>
          <div>
            <div class="text-xs mb-1">Price per m²</div>
            <div class="text-lg font-bold">CHF {{ calculatedPrice.pricePerSqm }}</div>
          </div>
          <div>
            <div class="text-xs mb-1">Total Price</div>
            <div class="text-lg font-bold">CHF {{ calculatedPrice.totalPrice }}</div>
          </div>
        </div>
      </div>
      <div v-else class="bg-white rounded-lg p-4 border border-gray-300 text-center text-gray-500 text-sm">
        No variation found for this combination
      </div>
    </div>

    <!-- Formula-Based Calculator -->
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
      <h3 class="font-semibold text-lg mb-3">Custom Dimensions Calculator</h3>
      <div class="text-sm text-gray-700 mb-3">Calculate prices for ANY dimensions using the pricing formula</div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium mb-1">Wood Type</label>
          <select v-model="formulaWood" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            <option v-for="wood in Object.keys(pricingFormulas).sort()" :key="wood" :value="wood">{{ wood }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Width (cm)</label>
          <input
            v-model.number="formulaWidth"
            type="number"
            min="50"
            max="150"
            class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
            placeholder="e.g., 75"
          />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Length (cm)</label>
          <input
            v-model.number="formulaLength"
            type="number"
            min="50"
            max="350"
            class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
            placeholder="e.g., 185"
          />
        </div>
      </div>

      <div v-if="formulaCalculatedPrice" class="bg-orange-100 rounded-lg p-4 border border-orange-300 text-orange-900">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
          <div>
            <div class="text-xs mb-1">Area</div>
            <div class="text-lg font-bold">{{ formulaCalculatedPrice.area }} m²</div>
          </div>
          <div>
            <div class="text-xs mb-1">Base Price</div>
            <div class="text-sm font-bold">CHF {{ formulaCalculatedPrice.basePrice }}</div>
          </div>
          <div>
            <div class="text-xs mb-1">Material Cost</div>
            <div class="text-sm font-bold">CHF {{ formulaCalculatedPrice.materialCost }}</div>
          </div>
          <div>
            <div class="text-xs mb-1">Total Price</div>
            <div class="text-2xl font-bold">CHF {{ formulaCalculatedPrice.totalPrice }}</div>
          </div>
        </div>
        <div class="mt-3 text-xs text-center">
          Formula: {{ formulaCalculatedPrice.basePrice }} (base) + {{ formulaCalculatedPrice.area }} m² × {{ formulaCalculatedPrice.rate }}/m² = CHF {{ formulaCalculatedPrice.totalPrice }}
        </div>
      </div>
      <div v-else class="bg-white rounded-lg p-4 border border-gray-300 text-center text-gray-500 text-sm">
        Enter valid dimensions to calculate price
      </div>
    </div>

    <!-- Wood Type Comparison -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
      <h3 class="font-semibold text-lg mb-3">Wood Type Price Comparison (100cm × 200cm)</h3>
      <div class="space-y-2">
        <div v-for="wood in woodTypePrices" :key="wood.name" class="flex items-center justify-between">
          <span class="text-sm">{{ wood.name }}</span>
          <div class="flex items-center gap-3">
            <span class="text-sm font-medium">CHF {{ wood.price }}</span>
            <span class="text-xs text-gray-600">({{ wood.pricePerSqm }}/m²)</span>
            <div class="w-32 bg-gray-200 rounded-full h-2">
              <div class="bg-blue-600 h-2 rounded-full" :style="{ width: wood.percentage + '%' }"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Volume Discount -->
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
      <h3 class="font-semibold text-lg mb-3">Volume Discount Pattern (100cm width)</h3>
      <div class="text-sm text-gray-700 mb-3">All wood types show volume discounts - price per m² decreases as area increases:</div>

      <div v-for="woodDiscount in volumeDiscountsByWood" :key="woodDiscount.wood" class="mb-4 last:mb-0">
        <div class="flex items-center justify-between mb-2">
          <h4 class="font-medium text-sm">{{ woodDiscount.wood }}</h4>
          <span class="text-xs bg-green-700 text-white px-2 py-0.5 rounded">{{ woodDiscount.discountPercent }}% discount (1m² → 3m²)</span>
        </div>
        <div class="grid grid-cols-5 gap-2">
          <div v-for="size in woodDiscount.sizes" :key="size.length" class="bg-white rounded p-2 text-center border border-green-300">
            <div class="text-xs text-gray-600">{{ size.length }}</div>
            <div class="text-sm font-bold text-green-700">{{ size.pricePerSqm }}</div>
            <div class="text-xs text-gray-500">per m²</div>
            <div class="text-xs font-medium mt-0.5">CHF {{ size.price }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Width Comparison -->
    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
      <h3 class="font-semibold text-lg mb-3">Width Impact (200cm length)</h3>
      <div class="text-sm text-gray-700 mb-3">Wider pieces also benefit from volume pricing:</div>

      <div v-for="woodWidth in widthComparisonByWood" :key="woodWidth.wood" class="mb-4 last:mb-0">
        <h4 class="font-medium text-sm mb-2">{{ woodWidth.wood }}</h4>
        <div class="space-y-1">
          <div v-for="width in woodWidth.widths" :key="width.width" class="flex items-center justify-between bg-white rounded px-3 py-2 border border-purple-300">
            <span class="text-sm">{{ width.width }} ({{ width.area }} m²)</span>
            <div class="flex items-center gap-3">
              <span class="text-sm font-medium">CHF {{ width.price }}</span>
              <span class="text-xs text-gray-600">({{ width.pricePerSqm }}/m²)</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  variations: {
    type: Array,
    required: true
  }
});

// Calculator state
const selectedWood = ref('Amerikanischer Nussbaum');
const selectedWidth = ref('100 cm');
const selectedLength = ref('200 cm');

// Formula calculator state
const formulaWood = ref('Amerikanischer Nussbaum');
const formulaWidth = ref(75);
const formulaLength = ref(185);

// Pricing formulas (derived from analysis)
const pricingFormulas = {
  'Ahorn': { basePrice: 2727.5, rate: 639.5 },
  'Amerikanischer Nussbaum': { basePrice: 2726, rate: 1378 },
  'Birnbaum': { basePrice: 2725.5, rate: 1124.5 },
  'Buche': { basePrice: 2726.5, rate: 519.5 },
  'Eiche': { basePrice: 2727.5, rate: 1012.5 },
  'Esche': { basePrice: 2727.5, rate: 604.5 },
  'Kirsche': { basePrice: 2725.5, rate: 1124.5 },
  'Linde': { basePrice: 2728, rate: 437 },
  'Nussbaum': { basePrice: 2727.5, rate: 1631.5 },
  'Tanne': { basePrice: 2727, rate: 452 },
  'Ulme': { basePrice: 2728.5, rate: 765.5 }
};

// Calculator computed properties
const availableWoodTypes = computed(() => {
  const woodTypes = new Set();
  props.variations.forEach(v => {
    const wood = v.attribute_values?.['Novatur - Holzart'];
    if (wood) woodTypes.add(wood);
  });
  return Array.from(woodTypes).sort();
});

const availableWidths = computed(() => {
  const widths = new Set();
  props.variations.forEach(v => {
    const width = v.attribute_values?.['Novatur Breite'];
    if (width) widths.add(width);
  });
  return Array.from(widths).sort((a, b) => parseInt(a) - parseInt(b));
});

const availableLengths = computed(() => {
  const lengths = new Set();
  props.variations.forEach(v => {
    const length = v.attribute_values?.['Novatur Länge'];
    if (length) lengths.add(length);
  });
  return Array.from(lengths).sort((a, b) => parseInt(a) - parseInt(b));
});

const calculatedPrice = computed(() => {
  const variation = props.variations.find(v =>
    v.attribute_values?.['Novatur - Holzart'] === selectedWood.value &&
    v.attribute_values?.['Novatur Breite'] === selectedWidth.value &&
    v.attribute_values?.['Novatur Länge'] === selectedLength.value
  );

  if (!variation || !variation.price) return null;

  const widthNum = parseInt(selectedWidth.value);
  const lengthNum = parseInt(selectedLength.value);
  const area = (widthNum * lengthNum) / 10000;
  const price = parseFloat(variation.price);

  return {
    area: area.toFixed(2),
    pricePerSqm: (price / area).toFixed(0),
    totalPrice: price.toFixed(0),
    inStock: variation.in_stock
  };
});

const formulaCalculatedPrice = computed(() => {
  if (!formulaWidth.value || !formulaLength.value || formulaWidth.value < 50 || formulaLength.value < 50) {
    return null;
  }

  const formula = pricingFormulas[formulaWood.value];
  if (!formula) return null;

  const area = (formulaWidth.value * formulaLength.value) / 10000;
  const materialCost = area * formula.rate;
  const totalPrice = formula.basePrice + materialCost;

  return {
    area: area.toFixed(2),
    basePrice: formula.basePrice.toFixed(0),
    rate: formula.rate.toFixed(0),
    materialCost: materialCost.toFixed(0),
    totalPrice: totalPrice.toFixed(0)
  };
});

// Analysis computed properties
const woodTypePrices = computed(() => {
  const targetWidth = '100 cm';
  const targetLength = '200 cm';
  const woodTypes = ['Buche', 'Esche', 'Ahorn', 'Eiche', 'Kirsche', 'Amerikanischer Nussbaum'];
  const prices = [];

  woodTypes.forEach(wood => {
    const variation = props.variations.find(v =>
      v.attribute_values?.['Novatur - Holzart'] === wood &&
      v.attribute_values?.['Novatur Breite'] === targetWidth &&
      v.attribute_values?.['Novatur Länge'] === targetLength
    );

    if (variation && variation.price) {
      const price = parseFloat(variation.price);
      prices.push({
        name: wood,
        price: price.toFixed(0),
        pricePerSqm: (price / 2).toFixed(0),
        rawPrice: price
      });
    }
  });

  const maxPrice = Math.max(...prices.map(p => p.rawPrice));
  prices.forEach(p => {
    p.percentage = (p.rawPrice / maxPrice * 100).toFixed(0);
  });

  return prices;
});

const volumeDiscountsByWood = computed(() => {
  const width = '100 cm';
  const lengths = ['100 cm', '150 cm', '200 cm', '250 cm', '300 cm'];
  const woodTypes = ['Buche', 'Eiche', 'Ahorn', 'Kirsche', 'Amerikanischer Nussbaum'];

  return woodTypes.map(wood => {
    const sizes = lengths.map(length => {
      const variation = props.variations.find(v =>
        v.attribute_values?.['Novatur - Holzart'] === wood &&
        v.attribute_values?.['Novatur Breite'] === width &&
        v.attribute_values?.['Novatur Länge'] === length
      );

      if (variation && variation.price) {
        const lengthNum = parseInt(length);
        const area = (lengthNum * 100) / 10000;
        const price = parseFloat(variation.price);

        return {
          length: length,
          area: area,
          price: price.toFixed(0),
          pricePerSqm: (price / area).toFixed(0),
          rawPricePerSqm: price / area
        };
      }
      return null;
    }).filter(Boolean);

    if (sizes.length > 0) {
      const firstPricePerSqm = sizes[0].rawPricePerSqm;
      const lastPricePerSqm = sizes[sizes.length - 1].rawPricePerSqm;
      const discountPercent = ((firstPricePerSqm - lastPricePerSqm) / firstPricePerSqm * 100).toFixed(1);

      return {
        wood: wood,
        sizes: sizes,
        discountPercent: discountPercent
      };
    }
    return null;
  }).filter(Boolean);
});

const widthComparisonByWood = computed(() => {
  const length = '200 cm';
  const widths = ['80 cm', '90 cm', '100 cm'];
  const woodTypes = ['Buche', 'Eiche', 'Amerikanischer Nussbaum'];

  return woodTypes.map(wood => {
    const widthData = widths.map(width => {
      const variation = props.variations.find(v =>
        v.attribute_values?.['Novatur - Holzart'] === wood &&
        v.attribute_values?.['Novatur Breite'] === width &&
        v.attribute_values?.['Novatur Länge'] === length
      );

      if (variation && variation.price) {
        const widthNum = parseInt(width);
        const area = (widthNum * 200) / 10000;
        const price = parseFloat(variation.price);

        return {
          width: width,
          area: area.toFixed(1),
          price: price.toFixed(0),
          pricePerSqm: (price / area).toFixed(0)
        };
      }
      return null;
    }).filter(Boolean);

    if (widthData.length > 0) {
      return {
        wood: wood,
        widths: widthData
      };
    }
    return null;
  }).filter(Boolean);
});
</script>
