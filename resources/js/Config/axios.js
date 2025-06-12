import axios from "axios";
import https from "stream-http";
import Cookies from "js-cookie"; 

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