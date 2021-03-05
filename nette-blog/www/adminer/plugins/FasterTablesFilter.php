<?php

/**
 * Faster tables filter plugin
 * ===========================
 * Useful when there's way too many tables than it shoud be and Adminer Tables Filter is slow
 *
 * @author Martin Macko, https://github.com/linkedlist
 * @license http://http://opensource.org/licenses/MIT, The MIT License (MIT)
 */
class FasterTablesFilter {

	function tablesPrint($tables) { ?>

		<p class="jsonly"><input id="filter-field">
			<style>
				.select-text {
					margin-right: 5px;
				}
			</style>
		<p id='tables'></p>
		<script type="text/javascript" <?php echo nonce(); ?>>
			function readCookie(name) {
				name = name.replace(/([.*+?^=!:${}()|[\]\/\\])/g, '\\$1');
				var regex = new RegExp('(?:^|;)\\s?' + name + '=(.*?)(?:;|$)', 'i'),
					match = document.cookie.match(regex);
				return match && unescape(match[1]);
			}

			var filterf = function () {
				var divProto = document.createElement('div');
				var aProto = document.createElement('a');
				var brProto = document.createElement('br');
				var tableDiv = document.getElementById("tables");

				function appendTables() {
					var fragment = document.createDocumentFragment();
					var item;
					for (var i = 0, len = tempTables.length; i < len; i++) {
						item = tempTables[i];
						var div = divProto.cloneNode();

						var aSelect = aProto.cloneNode();
						aSelect.href = hMe + "select=" + item;
						aSelect.innerHTML = langSelect;
						aSelect.className = "select-text";

						var aName = aProto.cloneNode();
						aName.href = hMe + "table=" + item;
						aName.text = item;
						div.appendChild(aSelect);

						div.appendChild(aName);
						var br = brProto.cloneNode();
						div.appendChild(br);
						fragment.appendChild(div);
					}
					tableDiv.appendChild(fragment);
				}

				var tables = [<?php foreach ($tables as $table => $type) {
					echo "'" . urlencode($table) . "'" . ",";
				}?>];
				var tempTables = tables;
				var hMe = "<?php echo h(ME) ?>";
				hMe = hMe.replace(/&amp;/g, '&');
				var langSelect = '<img height="16" src="data:image/png;base64, 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" alt="Red dot" />';
				var filterCookie = readCookie('tableFilter');

				var filter = document.getElementById("filter-field");
				if (filterCookie != '') {
					filter.value = filterCookie;
				}

				function filterTableList() {
					document.cookie = "tableFilter=" + filter.value
					while (tableDiv.firstChild) {
						tableDiv.removeChild(tableDiv.firstChild);
					}
					tempTables = [];
					var value = filter.value.toLowerCase();
					var item;
					for (var i = 0, len = tables.length; i < len; i++) {
						item = tables[i];
						if (item.toLowerCase().indexOf(value) > -1) {
							tempTables.push(item);
						}
					}

					appendTables();
				};

				filter.onkeyup = function (event) {
					filterTableList();
				}

				filterTableList();
			}
			window.onload = filterf;
		</script>
		<?php return true;
	}
}