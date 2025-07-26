import { useForm, usePage } from '@inertiajs/react';
import { Transition } from '@headlessui/react';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';

export default function NewsletterSubscriptionForm({ className = '' }) {
    const user = usePage().props.auth.user;

    const { data, setData, post, processing, recentlySuccessful } = useForm({
        is_subscribed: user.is_subscribed || false,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('subscription.toggle'));
    };

    return (
        <section className={className}>
            <header>
                <h2 className="text-lg font-medium text-gray-900">
                    Newsletter Subscription
                </h2>

                <p className="mt-1 text-sm text-gray-600">
                    Subscribe to receive daily Egypt news updates.
                </p>
            </header>

            <form onSubmit={submit} className="mt-6 space-y-6">
                <div className="flex items-center">
                    <input
                        id="is_subscribed"
                        type="checkbox"
                        className="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        checked={data.is_subscribed}
                        onChange={(e) => setData('is_subscribed', e.target.checked)}
                    />
                    <InputLabel htmlFor="is_subscribed" className="ml-2" value="Subscribe to Egypt News Newsletter" />
                </div>

                <div className="flex items-center gap-4">
                    <PrimaryButton disabled={processing}>
                        Save
                    </PrimaryButton>

                    <Transition
                        show={recentlySuccessful}
                        enter="transition ease-in-out"
                        enterFrom="opacity-0"
                        leave="transition ease-in-out"
                        leaveTo="opacity-0"
                    >
                        <p className="text-sm text-gray-600">
                            Saved.
                        </p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}