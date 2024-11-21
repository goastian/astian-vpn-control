import axios from "axios";
import https from "stream-http";
import Cookies from "js-cookie";

export const $server = axios.create({
    baseURL: process.env.MIX_APP_SERVER,
    timeout: 5000,
    withCredentials: true,
    httpsAgent: new https.Agent({ keepAlive: true }),
    headers: {
        Accept: "application/json",
        Authorization: "Bearer " + Cookies.get(process.env.MIX_APP_TOKEN),
        "X-LOCALTIME": Intl.DateTimeFormat().resolvedOptions().timeZone,
    },
});

export const $host = axios.create({
    baseURL: process.env.MIX_APP_URL,
    timeout: 5000,
    withCredentials: true,
    httpsAgent: new https.Agent({ keepAlive: true }),
    headers: {
        Accept: "application/json",
        Authorization: "Bearer " + Cookies.get(process.env.MIX_APP_TOKEN),
        "X-LOCALTIME": Intl.DateTimeFormat().resolvedOptions().timeZone,
    },
});

$server.interceptors.response.use(
    function (response) {
        return response;
    },
    function (error) {
        if (
            error.response.status === 401 &&
            error.response.request.responseURL.includes("/api/gateway/user")
        ) {
            return Promise.reject(error);
        }

        if (error.response.status === 401) {
            redirectTo();
        }
        return Promise.reject(error);
    }
);

$host.interceptors.response.use(
    function (response) {
        return response;
    },
    function (error) {
        if (error.response.status === 401) {
            redirectTo();
        }
        return Promise.reject(error);
    }
);

/**
 * Redirect if the user is no authenticated
 */
function redirectTo() {
    window.location.href = process.env.MIX_APP_REDIRECT_TO;
}
