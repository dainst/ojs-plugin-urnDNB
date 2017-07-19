/**
 * @defgroup plugins_pubIds_urndnb_js
 */
/**
 * @file plugins/pubIds/urndnb/js/checkNumber.js
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * DNB-Mod 2017 by Philipp Franck / DAI
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @brief Function for determining and adding the check number for URNdnbs
 */
(function($) {

	/**
	 * Get the last, check number.
	 * Algorithm (s. http://www.persistent-identifier.de/?link=316):
	 *  every URNdnb character is replaced with a number
	 *  according to the conversion table,
	 *  every number is multiplied by
	 *  it's position/index (beginning with 1),
	 *  the numbers' sum is calculated,
	 *  the sum is divided by the last number,
	 *  the last number of the quotient
	 *  before the decimal point is the check number.
	 */
	$('#checkNo').click(function() {
		var newURNdnb = '', urndnb,
				urndnbPrefix = $('[id^="urndnbPrefix"]').val(),
				urndnbSuffix = $('[id^="urndnbSuffix"]').val(),
				conversionTable = {
					'9': '41', '8': '9', '7': '8', '6': '7',
					'5': '6', '4': '5', '3': '4', '2': '3',
					'1': '2', '0': '1', 'a': '18', 'b': '14',
					'c': '19', 'd': '15', 'e': '16', 'f': '21',
					'g': '22', 'h': '23', 'i': '24', 'j': '25',
					'k': '42', 'l': '26', 'm': '27', 'n': '13',
					'o': '28', 'p': '29', 'q': '31', 'r': '12',
					's': '32', 't': '33', 'u': '11', 'v': '34',
					'w': '35', 'x': '36', 'y': '37', 'z': '38',
					'-': '39', ':': '17', '_': '43', '/': '45',
					'.': '47', '+': '49'
				},
				i, j, char, sum, lastNumber, quot, quotRound, quotString, newSuffix;

		urndnb = urndnbPrefix + urndnbSuffix;
		urndnb = urndnb.toLowerCase();
		for (i = 0; i < urndnb.length; i++) {
			char = urndnb.charAt(i);
			newURNdnb += conversionTable[char];
		}
		sum = 0;
		for (j = 1; j <= newURNdnb.length; j++) {
			sum = sum + (newURNdnb.charAt(j - 1) * j);
		}
		lastNumber = newURNdnb.charAt(newURNdnb.length - 1);
		quot = sum / lastNumber;
		quotRound = Math.floor(quot);
		quotString = quotRound.toString();
		newSuffix = urndnbSuffix + quotString.charAt(quotString.length - 1);
		$('[id^="urndnbSuffix"]').val(newSuffix);
	});

}(jQuery));
