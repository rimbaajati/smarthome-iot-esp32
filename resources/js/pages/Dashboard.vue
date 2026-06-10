<template>
    <div class="app" :class="{ dark: isDark }">
        <!-- AMBIENT -->
        <div class="scene" aria-hidden="true">
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
        </div>

        <!-- TOASTS -->
        <TransitionGroup name="toast" tag="div" class="toast-stack">
            <div
                v-for="t in toasts"
                :key="t.id"
                class="toast"
                :class="'toast-' + t.type"
            >
                <div class="toast-icon">
                    <svg
                        v-if="t.type === 'danger'"
                        xmlns="http://www.w3.org/2000/svg"
                        width="15"
                        height="15"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path
                            d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"
                        />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                    <svg
                        v-else
                        xmlns="http://www.w3.org/2000/svg"
                        width="15"
                        height="15"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
                <div class="toast-body">
                    <span class="toast-msg">{{ t.message }}</span>
                    <span class="toast-time">just now</span>
                </div>
            </div>
        </TransitionGroup>

        <!-- ── NAVBAR ── -->
        <nav class="navbar">
            <div class="navbar-inner">
                <div class="brand">
                    <div class="brand-icon">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="15"
                            height="15"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="white"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"
                            />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                    </div>
                    <span class="brand-name">AuraHome</span>
                </div>

                <!-- STATUS BAR (center, desktop only) -->
                <div class="status-bar">
                    <span
                        class="sb-dot"
                        :class="anyAlert ? 'dot-warn' : 'dot-ok'"
                    ></span>
                    <span class="sb-text">{{
                        anyAlert ? "Alert detected" : "All systems normal"
                    }}</span>
                    <span class="sb-sep">·</span>
                    <span class="sb-text">{{ activeCount }}/4 active</span>
                </div>

                <div class="nav-right">
                    <button
                        class="icon-btn"
                        @click="isDark = !isDark"
                        :title="isDark ? 'Light mode' : 'Dark mode'"
                    >
                        <Transition name="icon-swap" mode="out-in">
                            <svg
                                v-if="isDark"
                                key="sun"
                                xmlns="http://www.w3.org/2000/svg"
                                width="17"
                                height="17"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <circle cx="12" cy="12" r="4" />
                                <path
                                    d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"
                                />
                            </svg>
                            <svg
                                v-else
                                key="moon"
                                xmlns="http://www.w3.org/2000/svg"
                                width="17"
                                height="17"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                            </svg>
                        </Transition>
                    </button>
                    <button
                        class="sync-btn"
                        @click="fetchStatus(false)"
                    >
                        <svg
                            class="spin"
                            xmlns="http://www.w3.org/2000/svg"
                            width="14"
                            height="14"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"
                            />
                            <path d="M21 3v5h-5" />
                            <path
                                d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"
                            />
                            <path d="M8 16H3v5" />
                        </svg>
                        Live
                    </button>
                </div>
            </div>
        </nav>

        <!-- ── PAGE ── -->
        <div class="page">
            <!-- PAGE HEADER: tiny, utility -->
            <div class="page-header">
                <div class="ph-left">
                    <h1 class="ph-title">Live Sensors</h1>
                    <div class="ph-badge">
                        <span class="ph-dot"></span>
                        Auto-refresh 1s
                    </div>
                </div>
                <!-- mobile status summary -->
                <div class="ph-right">
                    <span
                        class="ph-stat"
                        :class="anyAlert ? 'ph-stat-warn' : 'ph-stat-ok'"
                    >
                        {{ anyAlert ? "⚠ Alert" : "✓ Normal" }}
                    </span>
                </div>
            </div>

            <!-- ── SENSOR CARDS ── -->
            <div class="cards">
                <!-- Door -->
                <div
                    class="card"
                    :class="[
                        'card-door',
                        {
                            'card-alert': status.pintu_terbuka,
                            'card-disabled': !settings.pintu_enabled,
                        },
                    ]"
                >
                    <div class="card-glow"></div>

                    <div class="card-head">
                        <div class="card-meta">
                            <div class="card-icon icon-door">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path
                                        d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"
                                    />
                                    <path d="M2 20h20" />
                                    <path d="M14 12v.01" />
                                </svg>
                            </div>
                            <div>
                                <div class="card-name">Main Door</div>
                                <div class="card-sub">GPIO 18 · magnetic</div>
                            </div>
                        </div>
                        <div
                            class="toggle"
                            :class="{ on: settings.pintu_enabled }"
                            @click="toggleSetting('pintu_enabled')"
                            role="switch"
                            :aria-checked="settings.pintu_enabled"
                        >
                            <div class="toggle-knob"></div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div
                            class="reading"
                            :class="
                                status.pintu_terbuka
                                    ? 'reading-warn'
                                    : 'reading-ok'
                            "
                        >
                            <div class="reading-dot"></div>
                            <span>{{
                                status.pintu_terbuka ? "OPENED" : "CLOSED"
                            }}</span>
                        </div>
                    </div>

                    <div class="card-foot">
                        <span class="foot-label">{{
                            settings.pintu_enabled
                                ? "Monitoring active"
                                : "Monitoring off"
                        }}</span>
                        <span
                            class="foot-alert"
                            v-if="
                                status.pintu_terbuka && settings.pintu_enabled
                            "
                            >Telegram sent</span
                        >
                    </div>
                </div>

                <!-- Smoke -->
                <div
                    class="card"
                    :class="[
                        'card-smoke',
                        {
                            'card-alert': status.asap_terdeteksi,
                            'card-disabled': !settings.asap_enabled,
                        },
                    ]"
                >
                    <div class="card-glow"></div>

                    <div class="card-head">
                        <div class="card-meta">
                            <div class="card-icon icon-smoke">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path
                                        d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <div class="card-name">Smoke / Gas</div>
                                <div class="card-sub">GPIO 21 · MQ-2</div>
                            </div>
                        </div>
                        <div
                            class="toggle"
                            :class="{ on: settings.asap_enabled }"
                            @click="toggleSetting('asap_enabled')"
                            role="switch"
                            :aria-checked="settings.asap_enabled"
                        >
                            <div class="toggle-knob"></div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div
                            class="reading"
                            :class="
                                status.asap_terdeteksi
                                    ? 'reading-warn'
                                    : 'reading-ok'
                            "
                        >
                            <div class="reading-dot"></div>
                            <span>{{
                                status.asap_terdeteksi ? "DANGER" : "SAFE"
                            }}</span>
                        </div>
                    </div>

                    <div class="card-foot">
                        <span class="foot-label">{{
                            settings.asap_enabled
                                ? "Monitoring active"
                                : "Monitoring off"
                        }}</span>
                        <span
                            class="foot-alert"
                            v-if="
                                status.asap_terdeteksi && settings.asap_enabled
                            "
                            >Emergency sent</span
                        >
                    </div>
                </div>

                <!-- Laundry -->
                <div
                    class="card"
                    :class="[
                        'card-laundry',
                        { 'card-disabled': !settings.jemuran_enabled },
                    ]"
                >
                    <div class="card-glow"></div>

                    <div class="card-head">
                        <div class="card-meta">
                            <div class="card-icon icon-laundry">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path
                                        d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <div class="card-name">Laundry</div>
                                <div class="card-sub">Servo · auto retract</div>
                            </div>
                        </div>
                        <div
                            class="toggle"
                            :class="{ on: settings.jemuran_enabled }"
                            @click="toggleSetting('jemuran_enabled')"
                            role="switch"
                            :aria-checked="settings.jemuran_enabled"
                        >
                            <div class="toggle-knob"></div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="reading reading-neutral">
                            <div class="reading-dot"></div>
                            <span>{{
                                status.jemuran_ditarik ? "RETRACTED" : "OUTSIDE"
                            }}</span>
                        </div>
                    </div>

                    <div class="card-foot">
                        <span class="foot-label">{{
                            settings.jemuran_enabled
                                ? "Auto mode active"
                                : "Auto mode off"
                        }}</span>
                    </div>
                </div>

                <!-- Weather -->
                <div
                    class="card"
                    :class="[
                        'card-weather',
                        { 'card-disabled': !settings.hujan_enabled },
                    ]"
                >
                    <div class="card-glow"></div>

                    <div class="card-head">
                        <div class="card-meta">
                            <div class="card-icon icon-weather">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path
                                        d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"
                                    />
                                    <path d="M16 14v6" />
                                    <path d="M8 14v6" />
                                    <path d="M12 16v6" />
                                </svg>
                            </div>
                            <div>
                                <div class="card-name">Weather</div>
                                <div class="card-sub">
                                    Rain sensor · exterior
                                </div>
                            </div>
                        </div>
                        <div
                            class="toggle"
                            :class="{ on: settings.hujan_enabled }"
                            @click="toggleSetting('hujan_enabled')"
                            role="switch"
                            :aria-checked="settings.hujan_enabled"
                        >
                            <div class="toggle-knob"></div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div
                            class="reading"
                            :class="
                                status.hujan_turun
                                    ? 'reading-info'
                                    : 'reading-ok'
                            "
                        >
                            <div class="reading-dot"></div>
                            <span>{{
                                status.hujan_turun ? "RAINING" : "CLEAR"
                            }}</span>
                        </div>
                    </div>

                    <div class="card-foot">
                        <span class="foot-label">{{
                            settings.hujan_enabled
                                ? "Detection active"
                                : "Detection off"
                        }}</span>
                    </div>
                </div>

                <!-- Temperature -->
                <div class="card card-temp">
                    <div class="card-glow"></div>
                    <div class="card-head">
                        <div class="card-meta">
                            <div class="card-icon icon-temp">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="card-name">Temperature</div>
                                <div class="card-sub">DHT22 Sensor</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="reading reading-neutral">
                            <span style="font-size: 2rem; font-weight: bold; letter-spacing: -1px; color: var(--text-1);">{{ status.suhu }}<span style="font-size: 1.2rem; color: var(--text-3); font-weight: 600;">°C</span></span>
                        </div>
                    </div>
                    <div class="card-foot">
                        <span class="foot-label">Live monitoring</span>
                    </div>
                </div>

                <!-- Humidity -->
                <div class="card card-humidity">
                    <div class="card-glow"></div>
                    <div class="card-head">
                        <div class="card-meta">
                            <div class="card-icon icon-humidity">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="card-name">Humidity</div>
                                <div class="card-sub">DHT22 Sensor</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="reading reading-neutral">
                            <span style="font-size: 2rem; font-weight: bold; letter-spacing: -1px; color: var(--text-1);">{{ status.kelembapan }}<span style="font-size: 1.2rem; color: var(--text-3); font-weight: 600;">%</span></span>
                        </div>
                    </div>
                    <div class="card-foot">
                        <span class="foot-label">Live monitoring</span>
                    </div>
                </div>
            </div>

            <!-- SYSTEM FOOTER ROW -->
            <div class="sys-row">
                <div class="sys-chip">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="11"
                        height="11"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <rect x="4" y="4" width="16" height="16" rx="2" />
                        <rect x="9" y="9" width="6" height="6" />
                        <path
                            d="M15 2v2M9 2v2M2 15h2M2 9h2M15 20v2M9 20v2M20 15h2M20 9h2"
                        />
                    </svg>
                    ESP32 DevKit V1
                </div>
                <div class="sys-chip">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="11"
                        height="11"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.72 16.92z"
                        />
                    </svg>
                    Telegram Alerts
                </div>
                <div class="sys-chip">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="11"
                        height="11"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    Sync every 1s
                </div>
            </div>

            <!-- ── EVENT LOG ── -->
            <div class="event-log">
                <div class="log-header">
                    <div class="log-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                        Event Log
                        <span class="log-count" v-if="eventLog.length > 0">{{ eventLog.length }}</span>
                    </div>
                    <button class="log-clear-btn" @click="eventLog = []" v-if="eventLog.length > 0">Clear</button>
                </div>

                <div class="log-empty" v-if="eventLog.length === 0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    <span>No events yet. System is monitoring...</span>
                </div>

                <TransitionGroup name="log-item" tag="div" class="log-list">
                    <div
                        v-for="ev in eventLog"
                        :key="ev.id"
                        class="log-item"
                        :class="'log-' + ev.type"
                    >
                        <div class="log-item-icon">
                            <svg v-if="ev.type === 'danger'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            <svg v-else-if="ev.type === 'info'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="log-item-body">
                            <span class="log-item-msg">{{ ev.message }}</span>
                            <span class="log-item-time">{{ ev.time }}</span>
                        </div>
                    </div>
                </TransitionGroup>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            isDark: true,
            status: {
                pintu_terbuka: 0,
                asap_terdeteksi: 0,
                hujan_turun: 0,
                jemuran_ditarik: 0,
                suhu: 0,
                kelembapan: 0,
            },
            settings: {
                pintu_enabled: true,
                asap_enabled: true,
                hujan_enabled: true,
                jemuran_enabled: true,
            },
            toasts: [],
            eventLog: [],
            loading: false,
        };
    },
    computed: {
        anyAlert() {
            return (
                (this.status.pintu_terbuka && this.settings.pintu_enabled) ||
                (this.status.asap_terdeteksi && this.settings.asap_enabled)
            );
        },
        activeCount() {
            return Object.values(this.settings).filter(Boolean).length;
        },
    },
    mounted() {
        this.fetchSettings();
        // Initial fetch — silent (no spinner)
        this.fetchStatus(true, false);
        // Auto-poll every 1s — silent (no spinner)
        setInterval(() => this.fetchStatus(false, false), 1000);
    },
    methods: {
        async fetchSettings() {
            try {
                const r = await axios.get("/api/smarthome/settings");
                if (r.data) this.settings = r.data;
            } catch (e) {
                console.error("Failed to fetch settings");
            }
        },
        async toggleSetting(key) {
            this.settings[key] = !this.settings[key];
            try {
                await axios.post("/api/smarthome/settings", {
                    [key]: this.settings[key],
                });
                if (!this.settings[key])
                    this.addToast("Monitoring paused.", "info");
            } catch (e) {
                console.error("Failed to save setting");
                this.settings[key] = !this.settings[key];
            }
        },
        addToast(message, type = "info") {
            const id = Date.now();
            const time = this.formatTime();
            // Push to floating toasts (these still disappear)
            this.toasts.push({ id, message, type });
            setTimeout(() => {
                this.toasts = this.toasts.filter((t) => t.id !== id);
            }, 5000);
            // Also push to permanent event log (newest on top)
            this.eventLog.unshift({ id, message, type, time });
        },
        formatTime() {
            const now = new Date();
            return now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        },
        async fetchStatus(isInitial = false, showLoading = true) {
            // Only spin the button when manually triggered by the user
            if (showLoading) this.loading = true;
            try {
                const r = await axios.get(
                    "/api/smarthome/status?t=" + Date.now(),
                );
                const next = r.data;
                if (!isInitial) {
                    if (
                        this.status.pintu_terbuka === 0 &&
                        next.pintu_terbuka === 1 &&
                        this.settings.pintu_enabled
                    )
                        this.addToast("Alert: Door Opened", "danger");
                    if (
                        this.status.asap_terdeteksi === 0 &&
                        next.asap_terdeteksi === 1 &&
                        this.settings.asap_enabled
                    )
                        this.addToast("Emergency: Smoke Detected", "danger");
                }
                this.status = next;
            } catch (e) {
                console.error("Failed:", e);
            } finally {
                if (showLoading) {
                    setTimeout(() => {
                        this.loading = false;
                    }, 600);
                }
            }
        },
    },
};
</script>

<style scoped>
/* ───────────────────────────────────────────
   TOKENS — Light
─────────────────────────────────────────── */
.app {
    --bg: #dfe5f0;
    --glass: rgba(255, 255, 255, 0.52);
    --glass-b: rgba(255, 255, 255, 0.72);
    --glass-sh: 0 4px 24px rgba(80, 100, 160, 0.1);
    --glass-sh-lg: 0 12px 40px rgba(80, 100, 160, 0.16);

    --text-1: #111827;
    --text-2: #374151;
    --text-3: #9ca3af;

    --orb-1: #b9d0ff;
    --orb-2: #d4b5fc;
    --orb-3: #a5eeff;

    --nav-bg: rgba(240, 244, 252, 0.75);
    --nav-b: rgba(255, 255, 255, 0.85);

    --green: #16a34a;
    --green-bg: rgba(22, 163, 74, 0.12);
    --red: #dc2626;
    --red-bg: rgba(220, 38, 38, 0.12);
    --blue: #2563eb;
    --blue-bg: rgba(37, 99, 235, 0.12);
    --sky: #0284c7;
    --sky-bg: rgba(2, 132, 199, 0.12);

    --tog-off: rgba(0, 0, 0, 0.14);
    --tog-on: #16a34a;

    font-family:
        -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    min-height: 100vh;
    background: var(--bg);
    color: var(--text-1);
    -webkit-font-smoothing: antialiased;
    position: relative;
    overflow-x: hidden;
    transition:
        background 0.35s,
        color 0.25s;
}

/* ── Dark overrides ── */
.app.dark {
    --bg: #0a0c14;
    --glass: rgba(255, 255, 255, 0.045);
    --glass-b: rgba(255, 255, 255, 0.09);
    --glass-sh: 0 4px 24px rgba(0, 0, 0, 0.4);
    --glass-sh-lg: 0 12px 40px rgba(0, 0, 0, 0.55);

    --text-1: #f1f5f9;
    --text-2: #94a3b8;
    --text-3: #475569;

    --orb-1: #0d2a6e;
    --orb-2: #1e0b52;
    --orb-3: #042538;

    --nav-bg: rgba(10, 12, 20, 0.8);
    --nav-b: rgba(255, 255, 255, 0.06);

    --tog-off: rgba(255, 255, 255, 0.12);
}

/* ───────────────────────────────────────────
   AMBIENT ORBS
─────────────────────────────────────────── */
.scene {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
}
.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(90px);
    opacity: 0.6;
    transition: background 0.5s;
}
.app.dark .orb {
    opacity: 0.35;
}
.orb-1 {
    width: 55vw;
    height: 55vw;
    top: -25%;
    left: -15%;
    background: var(--orb-1);
    animation: d1 20s ease-in-out infinite alternate;
}
.orb-2 {
    width: 45vw;
    height: 45vw;
    top: 5%;
    right: -10%;
    background: var(--orb-2);
    animation: d2 25s ease-in-out infinite alternate;
}
.orb-3 {
    width: 38vw;
    height: 38vw;
    bottom: -15%;
    left: 35%;
    background: var(--orb-3);
    animation: d3 17s ease-in-out infinite alternate;
}
@keyframes d1 {
    to {
        transform: translate(6vw, 8vh) scale(1.1);
    }
}
@keyframes d2 {
    to {
        transform: translate(-5vw, 6vh) scale(1.08);
    }
}
@keyframes d3 {
    to {
        transform: translate(4vw, -7vh) scale(1.12);
    }
}

/* ───────────────────────────────────────────
   NAVBAR
─────────────────────────────────────────── */
.navbar {
    position: sticky;
    top: 0;
    z-index: 100;
    background: var(--nav-bg);
    backdrop-filter: blur(22px) saturate(180%);
    -webkit-backdrop-filter: blur(22px) saturate(180%);
    border-bottom: 1px solid var(--nav-b);
}
.navbar-inner {
    max-width: 960px;
    margin: 0 auto;
    padding: 0 20px;
    height: 58px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.brand {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-shrink: 0;
}
.brand-icon {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: linear-gradient(135deg, #3b82f6, #7c3aed);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 10px rgba(59, 130, 246, 0.4);
}
.brand-name {
    font-size: 15px;
    font-weight: 700;
    letter-spacing: -0.3px;
    color: var(--text-1);
}

.status-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--text-2);
    background: var(--glass);
    border: 1px solid var(--glass-b);
    backdrop-filter: blur(10px);
    border-radius: 100px;
    padding: 5px 14px;
}
.sb-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
}
.dot-ok {
    background: var(--green);
    box-shadow: 0 0 6px var(--green);
    animation: blink 2.5s ease-in-out infinite;
}
.dot-warn {
    background: var(--red);
    box-shadow: 0 0 6px var(--red);
    animation: blink 0.8s ease-in-out infinite;
}
@keyframes blink {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.3;
    }
}
.sb-sep {
    color: var(--text-3);
}
.sb-text {
    white-space: nowrap;
}

.nav-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.icon-btn {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: var(--glass);
    border: 1px solid var(--glass-b);
    backdrop-filter: blur(8px);
    color: var(--text-2);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.18s;
}
.icon-btn:hover {
    color: var(--text-1);
    transform: scale(1.06);
}

.sync-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 0 14px;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, #3b82f6, #7c3aed);
    border: none;
    color: white;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(59, 130, 246, 0.35);
    transition: all 0.18s;
}
.sync-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(59, 130, 246, 0.45);
}
.sync-btn:disabled {
    opacity: 0.55;
    cursor: not-allowed;
    transform: none;
}

@media (max-width: 600px) {
    .status-bar {
        display: none;
    }
}

/* ───────────────────────────────────────────
   PAGE LAYOUT
─────────────────────────────────────────── */
.page {
    position: relative;
    z-index: 1;
    max-width: 960px;
    margin: 0 auto;
    padding: 28px 20px 60px;
}

/* ── Page header ── */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.ph-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.ph-title {
    font-size: 20px;
    font-weight: 700;
    letter-spacing: -0.4px;
    color: var(--text-1);
    margin: 0;
}
.ph-badge {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    color: var(--text-3);
    background: var(--glass);
    border: 1px solid var(--glass-b);
    backdrop-filter: blur(8px);
    border-radius: 100px;
    padding: 3px 10px;
}
.ph-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: var(--green);
    box-shadow: 0 0 5px var(--green);
    animation: blink 2s ease-in-out infinite;
}
.ph-stat {
    font-size: 12px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 100px;
}
.ph-stat-ok {
    background: var(--green-bg);
    color: var(--green);
}
.ph-stat-warn {
    background: var(--red-bg);
    color: var(--red);
}

/* ───────────────────────────────────────────
   CARD GRID — 2 col desktop, 1 col mobile
─────────────────────────────────────────── */
.cards {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
    margin-bottom: 20px;
}
@media (max-width: 520px) {
    .cards {
        grid-template-columns: 1fr;
        gap: 12px;
    }
}

/* ───────────────────────────────────────────
   CARD
─────────────────────────────────────────── */
.card {
    position: relative;
    background: var(--glass);
    border: 1px solid var(--glass-b);
    border-radius: 20px;
    backdrop-filter: blur(18px) saturate(140%);
    -webkit-backdrop-filter: blur(18px) saturate(140%);
    box-shadow: var(--glass-sh);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 14px;
    overflow: hidden;
    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease,
        opacity 0.2s ease,
        border-color 0.2s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: var(--glass-sh-lg);
}

/* disabled state — faded */
.card-disabled {
    opacity: 0.45;
}
.card-disabled:hover {
    transform: none;
}

/* alert — red pulse border */
.card-alert {
    border-color: rgba(220, 38, 38, 0.45) !important;
    animation: alert-pulse 2s ease-in-out infinite;
}
@keyframes alert-pulse {
    0%,
    100% {
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
    }
    50% {
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.15);
    }
}

/* per-card glow blob */
.card-glow {
    position: absolute;
    top: -40%;
    left: -20%;
    width: 60%;
    height: 60%;
    border-radius: 50%;
    filter: blur(35px);
    opacity: 0.18;
    pointer-events: none;
    transition: opacity 0.3s;
}
.app.dark .card-glow {
    opacity: 0.28;
}
.card-door .card-glow {
    background: #3b82f6;
}
.card-smoke .card-glow {
    background: #f97316;
}
.card-laundry .card-glow {
    background: #22c55e;
}
.card-weather .card-glow {
    background: #38bdf8;
}
.card-temp .card-glow {
    background: #f43f5e;
}
.card-humidity .card-glow {
    background: #0ea5e9;
}
.card-alert .card-glow {
    background: #ef4444 !important;
    opacity: 0.35 !important;
}

/* ── Card head ── */
.card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.card-meta {
    display: flex;
    align-items: center;
    gap: 11px;
}

.card-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.icon-door {
    background: var(--blue-bg);
    color: var(--blue);
}
.icon-smoke {
    background: rgba(249, 115, 22, 0.12);
    color: #f97316;
}
.icon-laundry {
    background: var(--green-bg);
    color: var(--green);
}
.icon-weather {
    background: var(--sky-bg);
    color: var(--sky);
}
.icon-temp {
    background: rgba(244, 63, 94, 0.12);
    color: #f43f5e;
}
.icon-humidity {
    background: rgba(14, 165, 233, 0.12);
    color: #0ea5e9;
}
.card-alert .card-icon {
    background: var(--red-bg) !important;
    color: var(--red) !important;
}

.card-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-1);
    line-height: 1.2;
}
.card-sub {
    font-size: 11px;
    color: var(--text-3);
    font-family: "SF Mono", "Fira Code", monospace;
    margin-top: 1px;
}

/* ── Card body — the STATUS reading ── */
.card-body {
    padding: 10px 0 4px;
    border-top: 1px solid var(--glass-b);
    border-bottom: 1px solid var(--glass-b);
}

.reading {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 22px;
    font-weight: 800;
    letter-spacing: -0.5px;
}
.reading-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}
.reading-ok {
    color: var(--green);
}
.reading-ok .reading-dot {
    background: var(--green);
    box-shadow: 0 0 8px var(--green);
    animation: blink 2.5s ease-in-out infinite;
}
.reading-warn {
    color: var(--red);
}
.reading-warn .reading-dot {
    background: var(--red);
    box-shadow: 0 0 8px var(--red);
    animation: blink 0.7s ease-in-out infinite;
}
.reading-neutral {
    color: var(--text-2);
}
.reading-neutral .reading-dot {
    background: var(--text-3);
}
.reading-info {
    color: var(--sky);
}
.reading-info .reading-dot {
    background: var(--sky);
    box-shadow: 0 0 8px var(--sky);
    animation: blink 2s ease-in-out infinite;
}

/* ── Card foot ── */
.card-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.foot-label {
    font-size: 11px;
    color: var(--text-3);
}
.foot-alert {
    font-size: 11px;
    font-weight: 600;
    color: var(--red);
}

/* ───────────────────────────────────────────
   TOGGLE
─────────────────────────────────────────── */
.toggle {
    width: 46px;
    height: 26px;
    border-radius: 26px;
    background: var(--tog-off);
    border: 1px solid var(--glass-b);
    position: relative;
    cursor: pointer;
    transition:
        background 0.25s,
        border-color 0.25s;
    flex-shrink: 0;
}
.toggle.on {
    background: var(--tog-on);
    border-color: transparent;
}
.toggle-knob {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: white;
    position: absolute;
    top: 2px;
    left: 2px;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.22);
}
.toggle.on .toggle-knob {
    transform: translateX(20px);
}

/* ───────────────────────────────────────────
   SYSTEM ROW
─────────────────────────────────────────── */
.sys-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.sys-chip {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    color: var(--text-3);
    background: var(--glass);
    border: 1px solid var(--glass-b);
    backdrop-filter: blur(8px);
    border-radius: 100px;
    padding: 5px 12px;
}

/* ───────────────────────────────────────────
   TOASTS
─────────────────────────────────────────── */
.toast-stack {
    position: fixed;
    bottom: 24px;
    right: 16px;
    z-index: 300;
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: flex-end;
}
.toast {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: var(--glass);
    border: 1px solid var(--glass-b);
    backdrop-filter: blur(24px) saturate(160%);
    -webkit-backdrop-filter: blur(24px) saturate(160%);
    border-radius: 14px;
    padding: 12px 16px;
    box-shadow: var(--glass-sh-lg);
    max-width: 280px;
}
.toast-body {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.toast-msg {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-1);
}
.toast-time {
    font-size: 11px;
    color: var(--text-3);
}
.toast-icon {
    flex-shrink: 0;
    margin-top: 1px;
    color: var(--text-2);
}
.toast-danger {
    border-color: rgba(220, 38, 38, 0.35);
}
.toast-danger .toast-icon,
.toast-danger .toast-msg {
    color: var(--red);
}

.toast-enter-active,
.toast-leave-active {
    transition: all 0.28s ease;
}
.toast-enter-from {
    opacity: 0;
    transform: translateX(16px) scale(0.95);
}
.toast-leave-to {
    opacity: 0;
    transform: translateX(16px) scale(0.9);
}

/* ───────────────────────────────────────────
   MISC ANIMATIONS
─────────────────────────────────────────── */
.spin {
    animation: spin 0.9s linear infinite;
}
@keyframes spin {
    100% {
        transform: rotate(360deg);
    }
}

.icon-swap-enter-active,
.icon-swap-leave-active {
    transition: all 0.18s ease;
}
.icon-swap-enter-from {
    opacity: 0;
    transform: rotate(-20deg) scale(0.7);
}
.icon-swap-leave-to {
    opacity: 0;
    transform: rotate(20deg) scale(0.7);
}

/* ───────────────────────────────────────────
   EVENT LOG
─────────────────────────────────────────── */
.event-log {
    margin-top: 24px;
    background: var(--glass);
    border: 1px solid var(--glass-b);
    backdrop-filter: blur(18px) saturate(140%);
    -webkit-backdrop-filter: blur(18px) saturate(140%);
    border-radius: 20px;
    padding: 20px;
    box-shadow: var(--glass-sh);
}

.log-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.log-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 700;
    color: var(--text-2);
    letter-spacing: 0.2px;
}

.log-count {
    background: var(--red-bg);
    color: var(--red);
    font-size: 11px;
    font-weight: 700;
    padding: 1px 7px;
    border-radius: 100px;
    min-width: 20px;
    text-align: center;
}

.log-clear-btn {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-3);
    background: transparent;
    border: 1px solid var(--glass-b);
    border-radius: 100px;
    padding: 4px 12px;
    cursor: pointer;
    transition: all 0.15s ease;
}
.log-clear-btn:hover {
    color: var(--red);
    border-color: rgba(220, 38, 38, 0.35);
    background: var(--red-bg);
}

/* Empty state */
.log-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 32px 0;
    color: var(--text-3);
    font-size: 13px;
}

/* Log list container */
.log-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    max-height: 360px;
    overflow-y: auto;
    /* custom scrollbar */
    scrollbar-width: thin;
    scrollbar-color: var(--glass-b) transparent;
}
.log-list::-webkit-scrollbar {
    width: 4px;
}
.log-list::-webkit-scrollbar-track {
    background: transparent;
}
.log-list::-webkit-scrollbar-thumb {
    background: var(--glass-b);
    border-radius: 4px;
}

/* Individual log item */
.log-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 12px;
    border: 1px solid var(--glass-b);
    background: rgba(255, 255, 255, 0.03);
    border-left-width: 3px;
    transition: background 0.15s ease;
}
.log-item:hover {
    background: rgba(255, 255, 255, 0.06);
}

/* Type color accents */
.log-danger {
    border-left-color: var(--red);
    background: var(--red-bg);
}
.log-danger:hover {
    background: rgba(220, 38, 38, 0.16);
}
.log-info {
    border-left-color: var(--blue);
    background: var(--blue-bg);
}
.log-info:hover {
    background: rgba(37, 99, 235, 0.16);
}
.log-success {
    border-left-color: var(--green);
    background: var(--green-bg);
}

/* Log icon */
.log-item-icon {
    flex-shrink: 0;
    margin-top: 1px;
}
.log-danger .log-item-icon { color: var(--red); }
.log-info .log-item-icon { color: var(--blue); }
.log-success .log-item-icon { color: var(--green); }

/* Log text content */
.log-item-body {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}
.log-item-msg {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-1);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.log-item-time {
    font-size: 11px;
    color: var(--text-3);
    font-family: "SF Mono", "Fira Code", monospace;
}

/* Slide-in animation for new log entries */
.log-item-enter-active {
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.log-item-enter-from {
    opacity: 0;
    transform: translateX(-12px) scale(0.97);
}
.log-item-leave-active {
    transition: all 0.2s ease;
}
.log-item-leave-to {
    opacity: 0;
    transform: translateX(12px);
}
</style>

