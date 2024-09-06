module.exports = {
	globDirectory: './',
	globPatterns: [
		'**/*.{png,PNG,css,php,json,js}'
	],
	swDest: 'sw.js',
	ignoreURLParametersMatching: [
		/^utm_/,
		/^fbclid$/
	]
};