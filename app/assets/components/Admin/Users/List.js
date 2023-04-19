import React, {useEffect, useState} from "react";
import { fetchUsers } from './services';
import Table from 'react-bootstrap/Table';
import Pagination from 'react-bootstrap/Pagination';

const AdminUsersList = () => {
  const [users, setUsers] = useState([]);
  const [isLoaded, setLoaded] = useState(false);
  const [page, setPage] = useState(1);

  const changePage = (pageNumber) => {
    console.log(pageNumber)
    setPage(pageNumber)
  }

  useEffect(() => {
    let isComponentMounted = true;
    fetchUsers().then(res => {
      if (isComponentMounted) {
        setUsers(res);
        setLoaded(true);
      }
    })
    return () => (isComponentMounted = true);
  }, []);

  if (isLoaded) {
    return (
      <>
        <UsersTable users={users.content} />
        <UsersPagination page={users.page} totalPages={users.totalPages} changePage={changePage}/>
      </>
    );
  } else {
    return (<></>)
  }
}

const UsersTable = ({users}) => {
  return (
    <Table striped bordered hover>
      <thead>
      <tr>
        <th>Email</th>
        <th>Roles</th>
        <th>Actions</th>
      </tr>
      </thead>
      <tbody>
      {users.map(user => (
        <tr key={user.id}>
          <td>{user.email}</td>
          <td>{user.roles.join(', ')}</td>
          <td>@mdo</td>
        </tr>
      ))}
      </tbody>
    </Table>
  );
}

const UsersPagination = ({page, totalPages, changePage}) => {
  const pages = [];

  for (let number = 1; number <= totalPages; number++) {
    pages.push(
      <Pagination.Item key={number} active={number === page} onClick={() => changePage(number)}>
        {number}
      </Pagination.Item>
    );
  }

  return (<Pagination>{pages}</Pagination>);
};

export default AdminUsersList;
