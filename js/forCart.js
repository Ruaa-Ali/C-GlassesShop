$(document).ready(function() {
			setSummary();

		});

		function setSummary() {
			var objects = $(".order-total");
			var qty = $(".qty");
			var total = 0;
			var price = 0;
			var items = 0;
			for (var obj of objects) {
				price = obj.innerText;
				price = price.replace('$', '')
				price = parseInt(price);
				total += price;
			}

			for (var obj of qty) {
				items += parseInt(obj.value);
			}

			$('#total-lg').text('$' + total);
			$('#items-count-lg').text(items);

			$('#total-sm').text('$' + total);
			$('#items-count-sm').text(items);

			$('#total-modal').text('$' + total);
			$('#items-count-modal').text(items);


			if (items != 0) {
				total += 5;
				$('#cost-sm').text('$' + total)
				$('#cost-lg').text('$' + total);
				$('#cost-modal').text('$' + total);
			};

		}

		function calcTotal(id) {
			qty = $('#item' + id + '-quantity').val();
			price = $('#item' + id + '-price').text();
			$('#item' + id + '-total').text('$' + qty * price);
			setSummary();
		}

		function checkOut(e, empty) {
			e.preventDefault();
			if (empty == true) {
				$('#address-alert').modal();
			} else {
				$('#confirm').modal();
			}
		}

		$('#confirm-checkout').on('click', function(e) {
			e.preventDefault();
			$("[name='submit']")[0].click();
		});