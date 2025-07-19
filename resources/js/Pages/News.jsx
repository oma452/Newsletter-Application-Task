import React from 'react';
import { Head } from '@inertiajs/react';

export default function News({ news }) {
    return (
        <>
            <Head title="Latest News" />

            <div className="max-w-4xl mx-auto p-6">
                <h1 className="text-3xl font-bold mb-6">Latest News</h1>

                {news.length === 0 ? (
                    <p>No news available right now.</p>
                ) : (
                    <ul className="space-y-4">
                        {news.map((item) => (
                            <li key={item.id} className="p-4 border rounded shadow-sm">
                                <h2 className="text-xl font-semibold">{item.title}</h2>
                                <p className="text-gray-600">{item.description}</p>
                                <p className="text-sm text-gray-500 mt-2">Published: {new Date(item.created_at).toLocaleString()}</p>
                            </li>
                        ))}
                    </ul>
                )}
            </div>
        </>
    );
}
