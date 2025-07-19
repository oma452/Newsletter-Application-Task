import React from 'react';
import { Head } from '@inertiajs/react';

export default function News({ news }) {
    return (
        <>
            <Head title="News" />

            <div className="max-w-6xl mx-auto px-4 py-8">
                <h1 className="text-3xl font-bold mb-6">Latest News About Egypt</h1>

                {news.length === 0 && (
                    <p>No news found.</p>
                )}

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {news.map((item, index) => (
                        <div key={index} className="bg-white shadow rounded-lg p-4">
                            {item.urlToImage && (
                                <img
                                    src={item.urlToImage}
                                    alt={item.title}
                                    className="w-full h-48 object-cover rounded"
                                />
                            )}
                            <h2 className="text-xl font-semibold mt-4">{item.title}</h2>
                            <p className="text-sm text-gray-600 mt-2">{item.description}</p>
                            <a
                                href={item.url}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="text-blue-500 mt-3 inline-block"
                            >
                                Read more →
                            </a>
                            <p className="text-xs text-gray-400 mt-1">
                                {new Date(item.published_at).toLocaleString()}
                            </p>
                        </div>
                    ))}
                </div>
            </div>
        </>
    );
}
