document.addEventListener('DOMContentLoaded', function() {
    const connectBtn = document.getElementById('connectBtn');
    const disconnectBtn = document.getElementById('disconnectBtn');
    const statusDiv = document.getElementById('status');
    const deviceInfoDiv = document.getElementById('deviceInfo');
    
    let bluetoothDevice = null;

    // Update connection status
    function updateStatus(connected) {
        if (connected) {
            statusDiv.textContent = `Connected to ${bluetoothDevice.name}`;
            statusDiv.className = 'connected';
            connectBtn.disabled = true;
            disconnectBtn.disabled = false;
            deviceInfoDiv.style.display = 'block';
            
            // Display device info
            document.getElementById('deviceName').textContent = bluetoothDevice.name || 'Unknown';
            document.getElementById('deviceId').textContent = bluetoothDevice.id;
            document.getElementById('deviceConnected').textContent = bluetoothDevice.gatt.connected ? 'Yes' : 'No';
        } else {
            statusDiv.textContent = 'Disconnected';
            statusDiv.className = 'disconnected';
            connectBtn.disabled = false;
            disconnectBtn.disabled = true;
            deviceInfoDiv.style.display = 'none';
        }
    }

    // Connect to any Bluetooth device
    connectBtn.addEventListener('click', async function() {
        try {
            statusDiv.textContent = 'Searching for Bluetooth devices...';
            
            // Request any Bluetooth device without filters
            bluetoothDevice = await navigator.bluetooth.requestDevice({
                acceptAllDevices: true
            });
            
            statusDiv.textContent = `Found: ${bluetoothDevice.name}. Connecting...`;
            
            // Connect to GATT server (optional)
            if (bluetoothDevice.gatt) {
                await bluetoothDevice.gatt.connect();
            }
            
            updateStatus(true);
            
            // Handle disconnection
            bluetoothDevice.addEventListener('gattserverdisconnected', () => {
                updateStatus(false);
            });
            
        } catch (error) {
            statusDiv.textContent = `Error: ${error.message}`;
            statusDiv.className = 'disconnected';
            console.error('Bluetooth error:', error);
        }
    });

    // Disconnect from device
    disconnectBtn.addEventListener('click', function() {
        if (bluetoothDevice && bluetoothDevice.gatt.connected) {
            bluetoothDevice.gatt.disconnect();
        }
        bluetoothDevice = null;
        updateStatus(false);
    });
});