<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Detail Transaksi Outlet</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body px-0 pt-0 pb-2">
    <span class="text-center mb-3"  id="status-alert"></span>
    <ul class="list-group list-group-flush border-top-0">
        <li class="list-group-item py-2">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1 me-2">
              <p class="my-1 quantity-text">Nama Customer</p>
              <h6 class="mb-0">{{ $data->trans_outlet_nama }}</h6>
            </div>
            <div class="flex-shrink-0 text-end">
              <p class="my-1 quantity-text fw-bold">Tanggal Transaksi</p>
              <p class="my-1 quantity-text">{{ tanggalIndoWaktuLidgkap($data->created_at) }}</p>
            </div>
        </li>
        @php $total = 0; @endphp
        
        @foreach ($data->transaction_outlet as $id => $item)
            @php $subtotal = $item['product_harga'] * $item['qty']; @endphp
            <li class="list-group-item py-2 cart-item">
              <div class="d-flex align-items-center">
                <div class="flex-grow-1 me-2">
                  <h6 class="mb-0">{{ $item->product->nama }}</h6>
                  <p class="my-1 quantity-text">{{ rupiah($item['product_harga']) }} x ({{ $item['qty'] }})</p>
                </div>
                <div class="flex-shrink-0">
                  <p class="my-1 quantity-text">{{ rupiah($item['product_harga'] * $item['qty']) }}</p>
                </div>
              </div>
            </li>
            @php $total += $subtotal; @endphp
        @endforeach
        
        <li class="list-group-item py-2">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1 me-2">
              <h6 class="mb-0">Total Transaksi</h6>
            </div>
            <div class="flex-shrink-0">
              <p class="my-1 quantity-text fw-bold">{{ rupiah($total) }}</p>
            </div>
        </li>
        <li class="list-group-item py-2">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1 me-2">
              <p class="my-1 quantity-text">Pembayaran</p>
              <h6 class="mb-0">{{ $data->trans_outlet_metode }}</h6>
            </div>
            <div class="flex-shrink-0">
              <p class="my-1 quantity-text fw-bold">{{ rupiah($data->trans_outlet_bayar) }}</p>
            </div>
        </li>
        @if ($data->trans_outlet_metode == "Cash")
        <li class="list-group-item py-2">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1 me-2">
              <h6 class="mb-0">Kembali</h6>
            </div>
            <div class="flex-shrink-0">
              <p class="my-1 quantity-text fw-bold">{{ rupiah($data->trans_outlet_kembali) }}</p>
            </div>
        </li>
        @endif
        @if ($data->trans_keterangan != null)
        <li class="list-group-item py-2">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1 me-2">
              <h6 class="mb-0">Keterangan</h6>
            </div>
            <div class="flex-shrink-0">
              <p class="my-1 quantity-text text-danger">{{ $data->trans_keterangan }}</p>
            </div>
        </li>
        @endif
    </ul>
</div>
<div class="modal-footer p-2">
    <!--<button role="button" class="btn btn-success" id="print-button">Cetak Struk</a>-->
        <!--<button id="connect-button" class="btn btn-success">-->
        <!--    Connect Printer-->
        <!--</button>-->
    @if ($printer == "iware_xs_80ul")
    <a href="{{ route('transaction.outlet.print', $data->id) }}" target="_blank" class="btn btn-primary me-2">
        <i class="ph-duotone ph-printer me-2"></i> Cetak Struk
    </a>
    @else
    <button id="print-button" onclick="printReceipt({{ json_encode($data) }})" class="btn btn-primary">
        Cetak Struk
    </button>
    @endif    
    
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>


<script>
         // UI element references
         const statusAlert = document.getElementById('status-alert')
         // Bluetooth variables
         let device, server, characteristic;
         // Common UUIDs for BLE printers. You may need to change these.
         const SERVICE_UUID = '000018f0-0000-1000-8000-00805f9b34fb';
         const CHARACTERISTIC_UUID = '00002af1-0000-1000-8000-00805f9b34fb';
      
         // Outlet data from Laravel controller (assuming it's available globally)
      // const outletData = <?php echo json_encode($data->outlet ?? []); ?>;
     
         const formatRupiah = (amount) => {
             if (typeof amount !== 'number') {
                 return 'Rp0';
             }
             // Use Intl.NumberFormat for proper Indonesian currency formatting
             const formatter = new Intl.NumberFormat('id-ID', {
                 style: 'decimal',
                 minimumFractionDigits: 0
             });
             return 'Rp' + formatter.format(amount);
         };
      
         /**
          * Helper function to format a date string.
          * @param {string} dateString The date string from the database.
          * @returns {string} The formatted date string.
          */
         const formatDate = (dateString) => {
             const date = new Date(dateString);
             const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
             return date.toLocaleDateString('id-ID', options);
         };
      
         /**
          * Generates the full set of ESC/POS commands for the receipt.
          * @param {object} receiptData The transaction data.
          * @returns {Promise<Uint8Array>} A promise that resolves with the ESC/POS commands.
          */
         async function generatePrintCommands(receiptData) {
             console.log(receiptData);
             statusAlert.textContent = 'Generating printer commands...';
             const encoder = new TextEncoder();
          
             // Function to create a simple two-column layout using manual spacing
             const createLine = (item, price) => {
                 const totalWidth = 32;
                 const priceStr = formatRupiah(price);
              
                 let shortItem = item;
                 const maxItemLength = totalWidth - priceStr.length - 1;
                 if (item.length > maxItemLength) {
                     shortItem = item.substring(0, maxItemLength - 3) + '...';
                 
                 const spacesNeeded = totalWidth - shortItem.length - priceStr.length;
                 return shortItem + ' '.repeat(spacesNeeded) + priceStr;
             };
         
             if (!receiptData || Object.keys(receiptData).length === 0) {
                 statusAlert.textContent = 'Error: Receipt data is empty.';
                 return new Uint8Array([]);
             
             let commands = [];
          
             // Header
             commands.push(
                 ...[0x1b, 0x40], // Initialize printer
                 ...[0x1b, 0x61, 0x01], // Set alignment to center
                 ...[0x1b, 0x45, 0x01], // Set bold on
                 ...encoder.encode('SRUUPUT KOPI - ' + outletData.nama + '\n'),
                 ...[0x1b, 0x45, 0x00], // Set bold off
                 ...[0x1B, 0x4D, 0x01], // Set small font on
                 ...encoder.encode(outletData.alamat_print + '\n' + outletData.alamat_print2 + '\n\n'),
                 ...[0x1B, 0x4D, 0x00], // Set small font on
                 ...[0x1b, 0x61, 0x00], // Set alignment to left
                 ...encoder.encode(receiptData.trans_outlet_nama + '\n'),
                 ...[0x1B, 0x4D, 0x01], // Set small font on
                 ...encoder.encode(formatDate(receiptData.created_at) + '\n'),
                 ...[0x1B, 0x4D, 0x00], // Set small font on
                 ...encoder.encode('--------------------------------\n'),
                 ...encoder.encode('ITEM'.padEnd(24) + 'SUBTOTAL\n'),
                 ...encoder.encode('--------------------------------\n')
             );
          
             // Items
             let calculatedSubtotal = 0;
             receiptData.transaction_outlet.forEach(item => {
                 const subtotal = item.product_harga * item.qty;
                 calculatedSubtotal += subtotal;
                 commands.push(...encoder.encode(createLine(item.product.nama, subtotal) + '\n'));
                 commands.push(...encoder.encode(formatRupiah(item.product.harga) + ' (x' + item.qty + ')\n\n'));
             });
          
             const calculatedTotal = calculatedSubtotal
             commands.push(
                 ...[
                     ...encoder.encode('--------------------------------\n'),
                     ...encoder.encode(createLine('TOTAL', calculatedTotal) + '\n'),
                 ]
             )
             // Conditional payment and change lines
             if (receiptData.trans_outlet_metode === "Cash") {
                 const calculatedKembali = (receiptData.trans_outlet_bayar ?? 0) - calculatedTotal;
                 commands.push(
                     ...encoder.encode(createLine('Cash', receiptData.trans_outlet_bayar) + '\n'),
                     ...encoder.encode(createLine('Kembali', calculatedKembali) + '\n\n')
                 );
             } else {
                  commands.push(
                     ...encoder.encode(createLine(receiptData.trans_outlet_metode, receiptData.trans_outlet_bayar) + '\n\n')
                 );
             
             // Footer
             commands.push(
                 ...[
                     ...[0x1b, 0x61, 0x01], // Set alignment to center
                     ...encoder.encode('Terima Kasih\n\n\n\n'),
                     // Cut paper
                     ...[0x1d, 0x56, 0x01]
                 ]
             )
             return new Uint8Array(commands);
         }
      
      
         async function printReceipt(transactionData) {
             statusAlert.textContent = 'Searching for a Bluetooth device...';
             let characteristic;
      
             try {
                 let device;
                 if (navigator.bluetooth && navigator.bluetooth.getDevices) {
                     const devices = await navigator.bluetooth.getDevices();
                     if (devices.length > 0) {
                         device = devices[0];
                         statusAlert.textContent = `Connecting to a previously paired device: "${device.name}"...`;
                     } else {
                         statusAlert.textContent = 'No previously paired device found. Please select a device from the list.';
                         device = await navigator.bluetooth.requestDevice({
                             acceptAllDevices: true,
                             optionalServices: [SERVICE_UUID]
                         });
                     }
                 } else {
                     statusAlert.textContent = 'Auto-connect is not supported in this browser. Please select a device from the list.';
                     device = await navigator.bluetooth.requestDevice({
                         acceptAllDevices: true,
                         optionalServices: [SERVICE_UUID]
                     });
                 }
      
                 statusAlert.textContent = `Connecting to "${device.name}"...`;
                 const server = await device.gatt.connect();
      
                 statusAlert.textContent = 'Getting the service...';
                 const service = await server.getPrimaryService(SERVICE_UUID);
      
                 statusAlert.textContent = 'Getting the characteristic...';
                 characteristic = await service.getCharacteristic(CHARACTERISTIC_UUID);
      
                 statusAlert.textContent = `Connected to "${device.name}"! Generating print commands...`;
                 const escPosCommands = await generatePrintCommands(transactionData);
      
                 statusAlert.textContent = 'Sending commands to printer...';
                 const chunkSize = 20;
                 for (let i = 0; i < escPosCommands.length; i += chunkSize) {
                     const chunk = escPosCommands.slice(i, i + chunkSize);
                     await characteristic.writeValueWithoutResponse(chunk);
                 }
      
                 statusAlert.textContent = 'Print job sent successfully!';
      
             } catch (error) {
                 statusAlert.textContent = `Error: ${error.message}`;
                 console.error('Print error:', error);
             }
     </script>