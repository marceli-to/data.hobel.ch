/**
 * Wood rates per m² in CHF
 * Extracted from Novatur product (wc_id: 9367)
 * 
 * Formula: Price = productBasePrice + (area × woodRate)
 * - productBasePrice: Defined per product (manufacturing, handling, etc.)
 * - woodRate: Cost per m² for the wood type (defined here)
 */
export const woodRates = {
  'Ahorn': 639.5,
  'Amerikanischer Nussbaum': 1378,
  'Birnbaum': 1124,
  'Buche': 519.5,
  'Eiche': 1012.5,
  'Esche': 604.5,
  'Kirsche': 1124.5,
  'Linde': 437,
  'Nussbaum': 1631.5,
  'Tanne': 452,
  'Ulme': 765.5,
};

/**
 * Get the rate per m² for a wood type
 * Falls back to Buche rate if wood not found
 */
export const getWoodRate = (wood) => {
  return woodRates[wood] || woodRates['Buche'];
};

/**
 * Get all available wood types
 */
export const getWoodTypes = () => {
  return Object.keys(woodRates).sort();
};

/**
 * Calculate material cost based on area and wood type
 * @param {number} areaSqm - Area in square meters
 * @param {string} wood - Wood type name
 * @returns {number} Material cost (add product base price for total)
 */
export const calculateMaterialCost = (areaSqm, wood) => {
  return areaSqm * getWoodRate(wood);
};
