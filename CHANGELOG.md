# Changelog
## [v2.0.0] - 2021.10.29

### Breaking
 - Removed Service\TrackingService.php and Subscriber\Tracking as they are no longer necessary once tracking is handled by Web Components

### Added
 - Added Shopware5.7 compatibility
 - Added Cart tracking script using Web Components API
 - Added Checkout tracking using `ff-checkout-tracking` element
 - Added SFTP upload

## [v1.0.5] - 2021.07.07
### Added
 - Added `is-navigation` attribute to main `ff-record-list` (one with subscribe=true) on category and manufacturer pages. This might help detecting whether Web Components should send navigation requests instead of search. 

### Fixed
- Event data coming from searchbox element is not URLencoded before redirecting to search result page

## [v1.0.4] - 2021.06.15
### Changed
 - Removed Theme_Compiler_Collect_Plugin_Javascript event listener. Now js files are placed in `Resources/frontend/js` and should be loaded automatically.

### Fixed
 - Use field `Name` instead of `Title` in ff-record template in record_list_slider.tpl.

## [v1.0.3] - 2021.05.28
### Changed
- Upgrade Web Components to version 4.0.3

### Fixed
 - Fix hiding similar-products tab causes an error if there is are no native similar article fetched from the Shopware backend for a given article 

## [v1.0.2] - 2021.05.18

### Added
- Implement `ff-similar-products` element. It replaces the default Shopware5 section `Similar Products` mechanism  

### Changed
- Upgrade Web Components to version 4.0.2

### Fixed
 - Fix Recommendation header does not hide when no records are returned from FACT-Finder
 - Fix hardcoded record-id in ff-recommendation element

## [v1.0.1] - 2021.04.21

### Added
- Added manufacturer products page support

### Changed
- Use built-in Shopware5 product slider widget with `ff-recommendation` element 

### Fixed
- An error is thrown when user tries to navigate to manufacturer products page with `Use FACT-Finder® on category pages?` configuration option enabled

## [v1.0.0] - 2021.04.09
Initial module release. Includes Web Components v4.0.1

[v2.0.0]: https://github.com/FACT-Finder-Web-Components/shopware5-plugin/releases/tag/v2.0.0
[v1.0.5]: https://github.com/FACT-Finder-Web-Components/shopware5-plugin/releases/tag/v1.0.5
[v1.0.4]: https://github.com/FACT-Finder-Web-Components/shopware5-plugin/releases/tag/v1.0.4
[v1.0.3]: https://github.com/FACT-Finder-Web-Components/shopware5-plugin/releases/tag/v1.0.3
[v1.0.2]: https://github.com/FACT-Finder-Web-Components/shopware5-plugin/releases/tag/v1.0.2
[v1.0.1]: https://github.com/FACT-Finder-Web-Components/shopware5-plugin/releases/tag/v1.0.1
[v1.0.0]: https://github.com/FACT-Finder-Web-Components/shopware5-plugin/releases/tag/v1.0.0
