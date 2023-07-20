import Axios from "axios"

const axios = Axios.create({
    baseURL: "https://localhost/api",
    withCredentials: true,
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/json",
        "Accept": "application/json"
    }
})

export default axios
