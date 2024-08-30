import React from 'react';

const UserList = ({ title, users }) => {
    return (
        <>
            <div className="flex justify-between">
                <h4 className="font-bold text-gray-700 text-md mb-2">{title}</h4>
            </div>
            <ul>
                {users.map((user) => (
                    <li key={user.id} className="user-item">
                        <img
                            src={user.avatar_url}
                            alt={user.login}
                        />
                        <span>{user.login}</span>
                    </li>
                ))}
            </ul>
        </>
    );
};

export default UserList;
