import merge from "lodash-es/merge";
import MoviePerson from "./movie-person";

const MovieActor = merge({}, MoviePerson, {
	name: {
		disallowDeletion: true,
	},
	url: {
		disallowDeletion: true,
	},
	image: {
		disallowDeletion: true,
	},
});
export default MovieActor;
