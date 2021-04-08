<?php

/**
 * Custom character sets in collation select boxes.
 *
 * @link https://github.com/pematon/adminer-plugins
 *
 * @author Peter Knut
 * @copyright 2015-2018 Pematon, s.r.o. (http://www.pematon.com/)
 */
class AdminerCollations {
	/** @var array */
	private $characterSets;

	/**
	 * @param array $characterSets Array of allowed character sets.
	 */
	public function __construct(array $characterSets = ["utf8mb4_general_ci", "ascii_general_ci"]) {
		$this->characterSets = $characterSets;
	}

	/**
	 * Prints HTML code inside <head>.
	 */
	public function head() {
		if (empty($this->characterSets)) {
			return;
		}

		?>

		<script <?php echo nonce(); ?>>
			(function (document) {
				"use strict";

				document.addEventListener("DOMContentLoaded", init, false);

				function init() {
					let selects = document.querySelectorAll("select[name='Collation'], select[name*='collation']");

					for (let i = 0; i < selects.length; i++) {
						replaceOptions(selects[i]);
					}
				}

				function replaceOptions(select) {
					let characterSets = [
						<?php
						echo "'" . implode(",\n'", $this->characterSets) . "'";
						?>
					];
					let selectedSet = getSelectedSet(select);
					let html = '';
					let hasSelected = false;
					let emptyCollationValue = '(porovnávání)';

					for (let i = 0; i < characterSets.length; i++) {
						let selValue = '';
						if (characterSets[i] !== emptyCollationValue) {
							selValue = characterSets[i];
						}
						if (characterSets[i] === selectedSet) {
							hasSelected = true;
							html += '<option selected="selected" value="' + selValue + '">' + characterSets[i] + '</option>';
						} else {
							html += '<option value="' + selValue + '">' + characterSets[i] + '</option>';
						}
					}

					let selValue = '';
					if (!hasSelected && selectedSet !== "") {
						if (selectedSet !== emptyCollationValue) {
							selValue = selectedSet;
						}
						html += '<option selected="selected" value="' + selValue + '">' + selectedSet + '</option>';
					}

					select.innerHTML = html;
				}

				function getSelectedSet(select) {
					let options = select.getElementsByTagName("option");

					for (let i = 0; i < options.length; i++) {
						if (options[i].selected) {
							return options[i].innerHTML.trim();
						}
					}

					return "";
				}
			})(document);

		</script>

		<?php
	}
}