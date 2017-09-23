# Latest users plugin to [Question2Answer](http://question2answer.org/)

Plugin add pages with latest registered and latest logged users with action IP. Plugin highlights duplicated IP addresses, so you can simply detect multi-accounts.

## Installation

Clone or download this repository to *qa-plugin* directory in your Q2A.
 
## Configuration

Please go to admin panel and `Plugins` tab (*/admin/plugins*). Next, search *Latest users* and click *settings* link next to plugin description. At the end set number of latest registered and logged users, and click save.

If you want disable page, please set number to 0.

After you enable pages, links show in `Users` page in submenu. Addresses to this pages are:
- */users/latest-registered*
- */users/latest-logged*

---

**Important notice about code!**
This code don't respect PHP PSR-2 standard, because Question2Answer unfortunately too don't respect this. Also, I couldn't design classes and functions in my own way, because most of them impose Question2Answer script.
