import axios from "axios";

const fetchUsers = async () => {
  const url = '/api/admin/user';
  const response = await axios.get(url);
  const data = await response.data;
  return data;
}

export { fetchUsers }
