/**
 * Script Name: JS Live Search and Filter feature
 * Description: This script provides Vanilla JS filtering and search capability, for a CPT listing Page. It has a fallback to PhP filter for browsers that do not support JS. The JS filter has no Dependencies but it is limited to filtering and searching elements already in the DOM. It does not fetch anything from DB. The advantage of this filter is speed and UX friendliness.
 * Original idea and code is from here @link https://codepen.io/hilmanski/pen/XWgZYYp?editors=1010 which I extended with fallback and filtering.
 * 
 * TOC
 * - DOM ELEMENTS
 * -- fix tab ordering
 * - FUNCTION DEFINITIONS
 * -- Toggle Section Headers
 * -- Live search
 * -- Filters
 * - FUNCTION EXECUTIONS
 * -- Filters
 * -- Search
 * 
 * NOTICE: 
 * - For this to work you have to wrap both Filters and QueryBlock in one container and add class .main-filter-query-wrapper to it
 * - If you want to show/hide section title depending on whether there are results in the search or filter, add .loop to the container holding the heading and query block
 * - If you want to use sticky filters feature, add .sticky class to the container in which the shortcode block is
*/

(function($) {
  $(document).ready(function() {

	/******************** DOM ELEMENTS *******************/

	const skipLink = document.querySelector(".skip-link");
	const primaryNavLinks = document.querySelectorAll(".menu-item a");
	const homeLink = document.querySelector(".site-logo a");

	const mainWrapper = document.querySelector(".main-filter-query-wrapper"); // ADD THIS CLASS TO MAIN CONTAINER IN THE GUTENBERG EDITOR
	const cards = mainWrapper.querySelectorAll(".hentry");
	const cardLinks = mainWrapper.querySelectorAll(".gb-container-link");
	const filterMenu = mainWrapper.querySelector(".mined-filter-menu");

	const filterButtons = filterMenu.querySelectorAll(".filter");
	const resetFilters = filterMenu.querySelector(".reset-filters");
	const searchReset = filterMenu.querySelector(".search-reset");
	const searchInput = filterMenu.querySelector("#searchbox");
	const sticky = mainWrapper.querySelector(".sticky"); // ADD THIS CLASS IF YOU WANT TO USE STICKY FILTERS FEATURE

	cards.forEach((element) => element.classList.add("is-visible"));

	// fix tab ordering
	skipLink.setAttribute("tabindex", 1);
	homeLink.setAttribute("tabindex", 1); // maknuti na pravom sajtu
	primaryNavLinks.forEach((element) => element.setAttribute("tabindex", 1));
	cardLinks.forEach((element) => element.setAttribute("tabindex", 3));

	/******************** FUNCTION DEFINITIONS *******************/

	/**
	 * Toggle Section Headers based on having or not having Content
	 */
	function toggleSectionHeadings() {
	  const sections = document.querySelectorAll(".loop"); // ADD THIS CLASS TO CONTAINER THAT HOLDS THE SECTION HEADER AND QUERY BLOCK
	  if (sections) {
		sections.forEach((section) => {
		  if (section.querySelectorAll(".cards .is-visible").length <= 0) {
			section.classList.add("is-hidden");
		  } else {
			section.classList.remove("is-hidden");
		  }
		}, false);
	  }
	}

	/**
	 * Live search shows and hides cards
	 */
	function liveSearch() {
	  let searchQuery = document.getElementById("searchbox").value;
	  // Use innerText if all contents are visible
	  // Use textContent for including hidden elements
	  for (let i = 0; i < cards.length; i++) {
		if (
		  cards[i].textContent.toLowerCase().includes(searchQuery.toLowerCase())
		) {
		  cards[i].classList.remove("is-hidden");
		  cards[i].classList.add("is-visible");
		  toggleSectionHeadings();
		} else {
		  cards[i].classList.add("is-hidden");
		  cards[i].classList.remove("is-visible");
		  toggleSectionHeadings();
		}
	  }
	}

	/**
	 * Filters show and hide the cards too
	 */
	function filterCards(company) {
	  // UX feedback for filters
	  let ifActive = Array.from(filterMenu.querySelectorAll(".active"));
	  ifActive.forEach((element) => element.classList.remove("active"));

	  // Actual filtering
	  for (let i = 0; i < cards.length; i++) {
		searchInput.value = ""; // First clean search in case it's not empty

		if (cards[i].classList.contains(company)) {
		  // If you belong to my collection
		  cards[i].classList.remove("is-hidden");
		  cards[i].classList.add("is-visible");
		  toggleSectionHeadings();
		} else if (!cards[i].classList.contains(company)) {
		  // If you are not in my collection
		  cards[i].classList.add("is-hidden");
		  cards[i].classList.remove("is-visible");
		  toggleSectionHeadings();
		}

		// Clicking filter reset link, reveal all cards
		resetFilters.addEventListener("click", () => {
		  cards[i].classList.remove("is-hidden");
		  cards[i].classList.add("is-visible");
		  filterButtons.forEach((item) => {
			item.classList.remove("active");
		  });
		  resetFilters.classList.add("active");
		  toggleSectionHeadings();
		});

		// Keyboard too
		resetFilters.addEventListener("keyup", (event) => {
		  if (event.key === "Enter") {
			cards[i].classList.remove("is-hidden");
			cards[i].classList.add("is-visible");
			filterButtons.forEach((item) => {
			  item.classList.remove("active");
			});
			resetFilters.classList.add("active");
			toggleSectionHeadings();
		  }
		});
	  }
	}

	/******************** FUNCTION EXECUTIONS *******************/

	// If the total height of the filter sidebar is less than the viewport, make it stick, and keep watching over the height on browser resize
	if (sticky) {
	  if (sticky.offsetHeight < window.innerHeight) {
		sticky.classList.add("sticky-element");
	  }
	  window.addEventListener("resize", () => {
		if (sticky.offsetHeight < window.innerHeight) {
		  sticky.classList.add("sticky-element");
		} else {
		  sticky.classList.remove("sticky-element");
		}
	  });
	}

	// Mouse filters
	filterButtons.forEach((item) => {
	  item.addEventListener("click", (event) => {
		event.preventDefault();
		scrollToTop();
		filterCards(php_vars.minerals_taxonomy + "-" + item.id);
		item.classList.toggle("active");
	  });
	});

	// Keyboard filters
	filterButtons.forEach((item) => {
	  item.addEventListener("keyup", (event) => {
		if (event.key === "Enter") {
		  event.preventDefault();
		  scrollToTop();
		  filterCards(php_vars.minerals_taxonomy + "-" + item.id);
		  item.classList.toggle("active");
		}
	  });
	});

	// Define the mineSearchAjax object
	var mineSearchAjax = {
	  ajaxurl: php_vars.ajaxurl.replace("admin-ajax.php", "/wp/v2/company/"),
	  nonce: php_vars.nonce,
	};

	// Search
	let typingTimer;
	let typeInterval = 500;

	searchInput.addEventListener("keyup", () => {
	  clearTimeout(typingTimer);
	  typingTimer = setTimeout(liveSearch, typeInterval, scrollToTop());
	});

	searchReset.addEventListener("keyup", () => {
	  searchInput.value = "";
	});

	// Scroll to top
	function scrollToTop() {
	  if (window.pageYOffset > window.innerHeight / 3) {
		// Do not scroll if there is no reason
		window.scroll({ top: 0, left: 0, behavior: "smooth" });
	  }
	}
  },
  false
);

jQuery(function ($) {
  // Define the mineSearchAjax object
  var mineSearchAjax = {
	ajaxurl: php_vars.ajaxurl,
	nonce: php_vars.nonce,
  };

  // Function to handle the AJAX request on changing the sort option
  function handleSortOptionChange() {
	console.log("Sort option changed");

	var sortOption = $("#company-sort").val(); // Get the selected sort option value
	var nonce = mineSearchAjax.nonce; // Get the nonce value

	// Perform the AJAX request
	$.ajax({
	  url: mineSearchAjax.ajaxurl,
	  type: "GET",
	  data: {
		action: "company_sort", // Update the action value to 'company_sort'
		nonce: nonce,
		sort_option: sortOption,
	  },
	  beforeSend: function () {
		// Show loading spinner or any other visual indicator
	  },
	  success: function (response) {
		if (response.success) {
		  var updatedContent = response.data; // Get the updated content
		  // Update the container element with the updated content
		  $("#company-container").html(updatedContent);
		} else {
		  // Handle error response
		  console.log(response.data);
		}
	  },
	  error: function (xhr, status, error) {
		// Handle AJAX error
		console.log(xhr.responseText);
	  },
	  complete: function () {
		// Hide loading spinner or any other visual indicator
	  },
	});
  }

  // Event listener for the sort option change event
  $('#company-sort').change(function() {
    var sortOption = $(this).val();
    var nonce = php_vars.nonce; // Get the nonce value from the localized PHP variables

    // AJAX request to handle sorting
    $.ajax({
      url: php_vars.ajaxurl, // Use the correct URL for your site
      method: 'GET',
      data: {
        action: 'company_search', // AJAX action hook
        nonce: nonce,
        sort_option: sortOption
      },
      beforeSend: function() {
        // Show loading indicator or disable the select element if needed
      },
      success: function(response) {
        if (response.success) {
          // Update the content with the sorted results
          $('#company-results').html(response.data);
        } else {
          // Handle the error case
          console.log(response.data);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        // Handle the AJAX request error
        console.log(textStatus + ': ' + errorThrown);
      },
      complete: function() {
        // Hide loading indicator or re-enable the select element if needed
      }
    });
  });
});

})(jQuery);
