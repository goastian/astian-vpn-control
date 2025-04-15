import axios from "axios";
import https from "stream-http";
import Cookies from "js-cookie";

export const $server = axios.create({
    baseURL: process.env.MIX_APP_SERVER,
    timeout: 10000,
    withCredentials: true,
    httpsAgent: new https.Agent({ keepAlive: true }),
    headers: {
        Accept: "application/json",
        "X-LOCALTIME": Intl.DateTimeFormat().resolvedOptions().timeZone,
    },
});

export const $api = axios.create({
    timeout: 10000,
    withCredentials: true, 
    httpsAgent: new https.Agent({ keepAlive: true }),
    headers: {
        Accept: "application/json",
        Authorization: Cookies.get(process.env.MIX_APP_TOKEN),
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
            error.response.request.responseURL.includes("/user")
        ) {
            return Promise.reject(error);
        }

        if (error.response.status === 401) {
            redirectTo();
        }
        return Promise.reject(error);
    }
);

$api.interceptors.response.use(
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
    //  window.location.href = process.env.MIX_APP_REDIRECT_TO;
}
