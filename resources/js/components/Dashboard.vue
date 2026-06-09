<template>
    <div style="font-family: sans-serif; padding: 20px">
        <h2>Smart Home Dashboard</h2>
        <div v-if="loading">Memuat data dari database...</div>

        <div v-else style="display: flex; gap: 20px; margin-top: 20px">
            <div
                style="
                    border: 1px solid #ccc;
                    padding: 15px;
                    border-radius: 8px;
                    width: 200px;
                "
            >
                <h3>Pintu Utama</h3>
                <h2 :style="{ color: status.pintu_terbuka ? 'red' : 'green' }">
                    {{ status.pintu_terbuka ? "TERBUKA" : "TERTUTUP" }}
                </h2>
            </div>

            <div
                style="
                    border: 1px solid #ccc;
                    padding: 15px;
                    border-radius: 8px;
                    width: 200px;
                "
            >
                <h3>Sensor Asap</h3>
                <h2
                    :style="{ color: status.asap_terdeteksi ? 'red' : 'green' }"
                >
                    {{ status.asap_terdeteksi ? "BAHAYA" : "AMAN" }}
                </h2>
            </div>

            <div
                style="
                    border: 1px solid #ccc;
                    padding: 15px;
                    border-radius: 8px;
                    width: 200px;
                "
            >
                <h3>Jemuran</h3>
                <h2 :style="{ color: status.hujan_turun ? 'blue' : 'orange' }">
                    {{ status.hujan_turun ? "DITARIK" : "DIJEMUR" }}
                </h2>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            status: {},
            loading: true,
        };
    },
    mounted() {
        this.fetchStatus();
        // Cek update otomatis setiap 2 detik
        setInterval(this.fetchStatus, 2000);
    },
    methods: {
        async fetchStatus() {
            try {
                // Menembak ke API lokalmu
                const response = await axios.get("/api/smarthome/status");
                this.status = response.data;
                this.loading = false;
            } catch (error) {
                console.error("Gagal mengambil data", error);
            }
        },
    },
};
</script>
