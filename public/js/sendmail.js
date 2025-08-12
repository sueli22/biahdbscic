 $(document).ready(function() {
     $('#mailTable').DataTable({
         pageLength: 10,
         lengthMenu: [5, 10, 25, 50],
         responsive: true
     });

     $('.show-detail').on('click', function() {
         $('#detailFrom').text($(this).data('from'));
         $('#detailDepartment').text($(this).data('department'));
         $('#detailTitle').text($(this).data('title'));
         $('#detailPhone').text($(this).data('phone'));
         $('#detailBody').text($(this).data('body'));

         let fileUrl = $(this).data('file');
         if (fileUrl) {
             $('#detailFile').attr('href', fileUrl).show();
         } else {
             $('#detailFile').hide();
         }
         var mailModal = new bootstrap.Modal(document.getElementById('mailDetailModal'));
         mailModal.show();
     });

     function extractNumber(str) {
         if (!str) return 0;

         str = String(str); // <-- convert input to string

         // Remove non-digit chars except Myanmar digits ၀-၉ and Arabic digits 0-9
         const cleaned = str.replace(/[^\d၀၁၂၃၄၅၆၇၈၉]/g, '');
         const myanmarDigits = '၀၁၂၃၄၅၆၇၈၉';
         let result = '';
         for (let ch of cleaned) {
             const idx = myanmarDigits.indexOf(ch);
             result += idx === -1 ? ch : idx;
         }
         return parseInt(result) || 0;
     }


     $('#excelFile').change(function(e) {
         var file = e.target.files[0];
         if (!file) return;

         var reader = new FileReader();
         reader.onload = function(e) {
             var data = new Uint8Array(e.target.result);
             var workbook = XLSX.read(data, {
                 type: 'array'
             });
             var firstSheet = workbook.SheetNames[0];
             var worksheet = workbook.Sheets[firstSheet];
             var jsonData = XLSX.utils.sheet_to_json(worksheet, {
                 header: 1
             });

             console.log(jsonData); // check your full excel data array

             var tbody = $('#excelTable tbody');
             tbody.empty();

             for (var i = 0; i < jsonData.length; i++) {
                 let row = jsonData[i + 1];

                 if (!row || row.length === 0) continue; // skip empty rows
                 let month = row[0] || '';
                 let type = row[1] || '';
                 let rawQty = row[2] || '';
                 let qty = extractNumber(rawQty);

                 let tr = `<tr>
                <td><input type="text" name="months[]" value="${month}" readonly class="form-control"></td>
                <td><input type="text" name="types[]" value="${type}" readonly class="form-control"></td>
                <td><input type="number" name="quantities[]" value="${qty}" readonly class="form-control"></td>
                <td><input type="number" name="unit_prices[]" value="1" min="1" required class="form-control"></td>
            </tr>`;

                 tbody.append(tr);
             }

             var modal = new bootstrap.Modal(document.getElementById('excelModal'));
             modal.show();
         };
         reader.readAsArrayBuffer(file);
     });

     var excelModalEl = document.getElementById('excelModal');
     excelModalEl.addEventListener('hidden.bs.modal', function() {
         $('#excelFile').val('');
     });

     $('.send-back-btn').on('click', function() {
         var to = $(this).data('from');
         var department = $(this).data('department');
         var phone = $(this).data('phone');;

         var modal = $('#sendBackModal');
         // Clear previous validation errors
         modal.find('.is-invalid').removeClass('is-invalid'); // Remove invalid styling
         modal.find('.invalid-feedback').text(''); // Clear error messages

         modal.find('input[name="to"]').val(to);
         modal.find('input[name="department"]').val(department);
         modal.find('input[name="phone"]').val(phone);

         // Show modal (if not already handled)
         modal.modal('show');
     });

 });
