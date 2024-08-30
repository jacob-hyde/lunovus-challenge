import React from 'react';

const UserHeader = ({ user }) => {
    return (
        <div className="flex justify-between py-4">
            <div className="flex items-center">
                <img
                    src={user.avatar_url}
                    alt={user.login}
                    className="w-10 h-10 rounded-full mr-4"
                />
                <h4 className="font-bold m-0 text-gray-700 text-sm text-center">
                    Handle: {user.login}
                </h4>
            </div>
            <h4 className="self-center font-bold m-0 text-gray-700 text-sm text-center">
                Follower Count: {user.followers}
            </h4>
        </div>
    );
};

export default UserHeader;
