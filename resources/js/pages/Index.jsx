import React, { useState, useEffect } from "react";
import axios from 'axios';

import GithubSearchInput from "../components/GithubSearchInput.jsx";
import LoadingBar from "../components/LoadingBar.jsx";
import UserList from "../components/UserList.jsx";
import UserHeader from "../components/UserHeader.jsx";
import LoadMoreButton from "../components/LoadMoreButton.jsx";

const Index = () => {
    const [userInput, setUserInput] = useState('');
    const [debounceUserInput, setDebounceUserInput] = useState('');
    const [user, setUser] = useState(null);
    const [users, setUsers] = useState([]);
    const [followers, setFollowers] = useState([]);
    const [nextPage, setNextPage] = useState(2);
    const [loading, setLoading] = useState(false);
    const [showLoadMore, setShowLoadMore] = useState(false);

    useEffect(() => {
        const timeoutId = setTimeout(() => {
            if (userInput !== debounceUserInput) {
                setDebounceUserInput(userInput);
            }
        }, 500);
        return () => clearTimeout(timeoutId);
    }, [userInput]);

    useEffect(() => {
        setNextPage(2);
        setFollowers([]);
        setUsers([]);
        setUser(null);

        if (!debounceUserInput) {
            return;
        }
        async function fetchUser() {
            setLoading(true);
            try {
                const { data: { data } } = await axios.get(`/api/github/search?q=${debounceUserInput}`);
                if (data?.total) {
                    setFollowers([]);
                    setUser(null);
                    setUsers(data.users);
                } else {
                    setFollowers([]);
                    setUsers([]);
                    setUser(data);
                    setFollowers(data.follower_users);
                    if (data?.followers <= 10) {
                        setShowLoadMore(false);
                    } else {
                        setShowLoadMore(true);
                    }
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            } finally {
                setLoading(false);
            }
        }

        fetchUser();
    }, [debounceUserInput]);

    const loadMoreFollowers = async () => {
        if (!user) {
            return;
        }
        setLoading(true);
        try {
            const { data: { data } } = await axios.get(`/api/github/${user.login}/followers?page=${nextPage}&total=${user?.followers}`);
            if (data?.followers) {
                setFollowers([...followers, ...data?.followers]);
                setNextPage(data?.next_page);
                if (!data?.next_page) {
                    setShowLoadMore(false);
                }
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        } finally {
            setLoading(false);
        }
    }

    return (
        <div className="lonovus-container">
            <div className="card">
                <div className="card-title">Github Followers</div>
                <GithubSearchInput userInput={userInput} setUserInput={setUserInput} />

                <div className="user-list">
                    {followers.length ? (
                        <>
                            <UserHeader user={user} />
                            <UserList title="Followers:" users={followers} />
                        </>
                    ) : (
                        !loading ? (
                        users.length ? (
                                <UserList title="Suggestions:" users={users.slice(0, 10)} />
                            ) : (
                            debounceUserInput ? (
                                <div className="text-center text-gray-700 py-4"><strong>{debounceUserInput}</strong> not found!</div>
                            ) : (
                                <div className="text-center text-gray-700 py-4">Please enter a username</div>
                                )
                            )
                        ) : null
                    )}
                </div>

                {loading && <LoadingBar loading={loading} />}

                <LoadMoreButton click={loadMoreFollowers} show={showLoadMore} />
            </div>
        </div>
    );
};

export default Index;
