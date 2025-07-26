import { useForm, usePage } from '@inertiajs/react';
import { Transition } from '@headlessui/react';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';

export default function NewsletterPreferencesForm({ className = '' }) {
    const user = usePage().props.auth.user;
    const preferences = user.preferences || {};

    const { data, setData, post, processing, recentlySuccessful } = useForm({
        categories: preferences.categories || [],
        keywords: preferences.keywords || [],
        frequency: preferences.frequency || 'daily',
    });

    const availableCategories = [
        'politics',
        'business', 
        'sports',
        'technology',
        'health',
        'entertainment'
    ];

    const handleCategoryChange = (category) => {
        const newCategories = data.categories.includes(category)
            ? data.categories.filter(c => c !== category)
            : [...data.categories, category];
        setData('categories', newCategories);
    };

    const handleKeywordChange = (e) => {
        const keywords = e.target.value.split(',').map(k => k.trim()).filter(k => k);
        setData('keywords', keywords);
    };

    const submit = (e) => {
        e.preventDefault();
        post(route('preferences.update'));
    };

    return (
        <section className={className}>
            <header>
                <h2 className="text-lg font-medium text-gray-900">
                    Newsletter Preferences
                </h2>
                <p className="mt-1 text-sm text-gray-600">
                    Customize your newsletter content and delivery preferences.
                </p>
            </header>

            <form onSubmit={submit} className="mt-6 space-y-6">
                {/* Categories */}
                <div>
                    <InputLabel value="News Categories" />
                    <div className="mt-2 grid grid-cols-2 gap-2">
                        {availableCategories.map((category) => (
                            <label key={category} className="flex items-center">
                                <input
                                    type="checkbox"
                                    className="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    checked={data.categories.includes(category)}
                                    onChange={() => handleCategoryChange(category)}
                                />
                                <span className="ml-2 text-sm text-gray-700 capitalize">
                                    {category}
                                </span>
                            </label>
                        ))}
                    </div>
                </div>

                {/* Keywords */}
                <div>
                    <InputLabel htmlFor="keywords" value="Keywords (comma-separated)" />
                    <input
                        id="keywords"
                        type="text"
                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="tourism, culture, economy"
                        value={data.keywords.join(', ')}
                        onChange={handleKeywordChange}
                    />
                    <p className="mt-1 text-sm text-gray-500">
                        Enter keywords you're interested in, separated by commas
                    </p>
                </div>

                {/* Frequency */}
                <div>
                    <InputLabel htmlFor="frequency" value="Delivery Frequency" />
                    <select
                        id="frequency"
                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value={data.frequency}
                        onChange={(e) => setData('frequency', e.target.value)}
                    >
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                    </select>
                </div>

                <div className="flex items-center gap-4">
                    <PrimaryButton disabled={processing}>
                        Save Preferences
                    </PrimaryButton>

                    <Transition
                        show={recentlySuccessful}
                        enter="transition ease-in-out"
                        enterFrom="opacity-0"
                        leave="transition ease-in-out"
                        leaveTo="opacity-0"
                    >
                        <p className="text-sm text-gray-600">
                            Preferences saved.
                        </p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}