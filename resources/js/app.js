import "./bootstrap";
import { createApp } from "vue";
import Dashboard from "./pages/Dashboard.vue";

const app = createApp({});
app.component("dashboard-component", Dashboard);
app.mount("#app");
